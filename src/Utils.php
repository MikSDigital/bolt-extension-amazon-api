<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

/**
 * Utility class for API look ups and storage
 *
 * @author    Gawain Lynch <gawain.lynch@gmail.com>
 * @copyright Copyright (c) 2015, Gawain Lynch
 */
class Utils
{
    /** @var Application */
    private $app;

    /** @var array */
    private $config;

    /** @var integer */
    private $ticktock;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        // Get some inheritance happening
        $this->app = $app;

        $this->config = $this->app['extensions.' . Extension::NAME]->config;
    }

    /**
     * @param array $response
     *
     * @return array
     */
    public function getFormattedArray(array $response)
    {
        // Shorten the arrays a bit
        $item = $response['Items']['Item'];
        $attr =  $response['Items']['Item']['ItemAttributes'];

        if (isset($attr['Author'])) {
            if (is_array($attr['Author'])) {
                $author = implode(", ", $attr['Author']);
            } else {
                $author = $attr['Author'];
            }
        } elseif (isset($attr['Creator'])) {
            if ($this->is_assoc($attr['Creator'])) {
                $author = $attr['Creator']['_'] . " ({$attr['Creator']['Role']})";
            } else {
                $arr = array();
                foreach ($attr['Creator'] as $creator) {
                    $arr[] = $creator['_'] . " ({$creator['Role']})";
                }
                $author = implode(', ', $arr);
            }
        } else {
            // If we got here, Amazon doesn't have any author/creator information...  It happens.
            $author = "Amazon Unspecified";
        }

        $retarr = array(
            'asin'       => (string) $item['ASIN'],
            'detail_url' => (string) $item['DetailPageURL'],

            'author'    => (string) $author,
            'title'     => (string) $item['ItemAttributes']['Title'],
            'publisher' => (string) $item['ItemAttributes']['Manufacturer'],

            'image_small_url'    => (string) $item['SmallImage']['URL'],
            'image_small_height' => (string) $item['SmallImage']['Height']['_'],
            'image_small_width'  => (string) $item['SmallImage']['Width']['_'],

            'image_medium_url'    => (string) $item['MediumImage']['URL'],
            'image_medium_height' => (string) $item['MediumImage']['Height']['_'],
            'image_medium_width'  => (string) $item['MediumImage']['Width']['_'],

            'image_large_url'    => (string) $item['LargeImage']['URL'],
            'image_large_height' => (string) $item['LargeImage']['Height']['_'],
            'image_large_width'  => (string) $item['LargeImage']['Width']['_'],

        );

        // Store the name of the locally saved image
        $retarr['image_local'] = $this->getLocalImageName($retarr);

        if (in_array('Medium', $this->config['responsegroup']) || in_array('Large', $this->config['responsegroup'])) {

            // ABIS_BOOK == Paperback or hardcover, ABIS_EBOOKS == Kindle etc
            $retarr['type'] = (string) $attr['ProductTypeName'];
            if ($retarr['type'] == 'ABIS_BOOK') {
                $retarr['isbn'] = (string) $attr['ISBN'];
            } elseif ($retarr['type'] == 'ABIS_EBOOK') {
                $retarr['isbn'] = (string) $attr['EISBN'];
            }

            $retarr['binding'] = isset($attr['Binding']) ? (string) $attr['Binding'] : '';
            $retarr['publication_date'] = isset($attr['PublicationDate']) ? (string) $attr['PublicationDate'] : '';
            $retarr['pages'] = isset($attr['NumberOfPages']) ? (int) $attr['NumberOfPages'] : 0;
            $retarr['edition'] = isset($attr['Edition']) ? (string) $attr['Edition'] : '';

            // Go Amazon... >.<
            $price = isset($attr['ListPrice']['FormattedPrice']) ? $attr['ListPrice']['FormattedPrice'] : '';
            $curr = isset($attr['ListPrice']['CurrencyCode']) ? $attr['ListPrice']['CurrencyCode'] : '';
            $retarr['price'] = trim((string) $price . ' ' . (string) $curr);

            /*
             * Amazon passes back alternative version of a book, such as 'Paperback',
             * 'Hardcover', or 'Kindle Edition'.
             *
             * If there is only one, it is returned in an associative array, multiples
             * are returned in an indexed array.  We need to handle that here.
             */
            if (isset($item['AlternateVersions']['AlternateVersion']) &&
                is_array($item['AlternateVersions']['AlternateVersion']) &&
                $this->is_assoc($item['AlternateVersions']['AlternateVersion'])) {
                $retarr['alternateversions'][0] = $item['AlternateVersions']['AlternateVersion'];
            } else {
                $retarr['alternateversions'] = $item['AlternateVersions']['AlternateVersion'];
            }

            $retarr['review'] = isset($item['EditorialReviews']['EditorialReview']['Content']) ?
                                        (string) $item['EditorialReviews']['EditorialReview']['Content'] :
                                        '';
        }

        return $retarr;
    }

    /**
     * Extract an image name to use locally
     *
     * @param array $response
     *
     * @return void|string
     */
    public function getLocalImageName(array $response)
    {
        if (!empty($response['image_large_url'])) {
            $imageinfo = pathinfo($response['image_large_url']);
        } elseif (!empty($response['image_medium_url'])) {
            $imageinfo = pathinfo($response['image_medium_url']);
        } elseif (!empty($response['image_small_url'])) {
            $imageinfo = pathinfo($response['image_small_url']);
        } else {
            // No image data
            return false;
        }

        $authors = $response['author'];
        $filename = 'books/' . $this->app['slugify']->slugify($authors . '-' . $response['title']) . '.' . $imageinfo['extension'];

        return $filename;
    }

    /**
     * Check that our remote exists
     *
     * @param string $url
     *
     * @return boolean
     */
    public function remoteExists($url)
    {
        $headers = array('User-Agent' => $this->config['user_agent']);

        try {
            // Throttle the request, if required
            $this->requestThrottle();

            $response = $this->app['guzzle.client']->head($url, $headers)->send();
        } catch (\Exception $e) {
            $this->app['logger.system']->critical("Remote check failed for $url " . $e->getMessage(), array('event' => 'exception', 'exception' => $e));
            return false;
        }

        // >= 400 is not found, 200 is found.
        // Some requests are returning a 403 so we only care about a 404
        if ($response->isError()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Download a file from a given URL to a given destination file/location
     *
     * @param string $url
     * @param string $dst
     *
     * @return Utils
     */
    public function downloadFile($url, $dst)
    {
        $headers = array('User-Agent' => $this->config['user_agent']);
        $params  = array('save_to' => $dst);

        try {
            // Throttle the request, if required
            $this->requestThrottle();

            $this->app['guzzle.client']->get($url, $headers, $params)->send();

            if (file_exists($dst)) {
                $this->app['logger.system']->info("Saved image file: $dst", array('event' => 'amazon'));
                $this->setFilePerms($dst);
            } else {
                $this->app['logger.system']->info("Download file missing from: $url", array('event' => 'amazon'));
            }
        } catch (\Exception $e) {
            $this->app['logger.system']->critical("Download failed for $url " . $e->getMessage(), array('event' => 'exception', 'exception' => $e));
        }

        return $this;
    }

    /**
     * Set the file permissions to the same as the directory it is contained in.
     *
     * Only really a problem when we're called via Nut
     *
     * @param string $file
     */
    public function setFilePerms($file)
    {
        $dir = dirname($file);

        if (file_exists($dir)) {
            $dirowner = fileowner($dir);
            $dirgroup = filegroup($dir);

            chown($file, $dirowner);
            chgrp($file, $dirgroup);
        }
    }

    /**
     * Is an array associative
     *
     * @param array $a
     *
     * @return boolean
     */
    private function is_assoc($a)
    {
        foreach (array_keys($a) as $key) {
            if (!is_int($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ensure we don't fire more than once per second
     *
     * @return Utils
     */
    public function requestThrottle()
    {
        // If our request timer isn't set, this is the first call and we're good
        if (!$this->ticktock) {
            $this->ticktock = microtime(true);

            return $this;
        }

        //
        $since = microtime(true) - $this->ticktock;
        if ($since < 1) {
            // Sleep for a second
            $snooze = 1000000 * (1 - $since);
            usleep($snooze);
        }

        // Update our timer
        $this->ticktock = microtime(true);

        return $this;
    }

    /**
     * Get the opposing formats ASIN
     *
     * @param array  $response
     * @param string $format   Either 'physical' or 'disgital'
     *
     * @return string
     */
    public function getAltVersionASIN($response, $format)
    {
        if (!isset($response['alternateversions'])) {
            return '';
        }

        foreach ($response['alternateversions'] as $alt) {
            if ($format == 'physical' && ($alt['Binding'] == 'Hardcover' || $alt['Binding'] == 'Paperback')) {
                return $alt['ASIN'];
            } elseif ($format == 'digital' && $alt['Binding'] == 'Kindle Edition') {
                return $alt['ASIN'];
            }
        }

        return '';
    }
}
