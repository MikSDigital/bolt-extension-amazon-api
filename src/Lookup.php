<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use ApaiIO\ApaiIO;
use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Lookup as ApaiIOLookup;
use ApaiIO\ResponseTransformer\ObjectToArray;
use Bolt\Extension\Bolt\AmazonApi\Exception\InvalidConfigurationException;
use Silex\Application;

/**
 * Amazon query request class
 *
 * @author    Gawain Lynch <gawain.lynch@gmail.com>
 * @copyright Copyright (c) 2015, Gawain Lynch
 */
class Lookup
{
    /** @var Application */
    private $app;

    /** @var array */
    private $config;

    /** @var \ApaiIO\ApaiIO */
    private $apaiIO;

    /**
     * @param Extension $extension
     */
    public function __construct(Application $app)
    {
        // Get some inheritance happening
        $this->app = $app;

        $this->config = $this->app['extensions.' . Extension::NAME]->config;
    }

    /**
     * Find an ASIN record, either from database cache or Amazon
     *
     * @param string  $asin
     * @param boolean $usecache
     *
     * @return array
     */
    public function doLookupASIN($asin, $usecache = true)
    {
        // Check first in the database for a cached version
        if ($usecache) {
            $response = $this->app['amazon.records']->doLookupASIN($asin);
        }

        if (empty($response)) {
            $response = $this->doAmazonRequest($asin);
        }

        return $response;
    }

    /**
     * Re-query Amazon for each cached ASIN and update our cache with any changed
     * information
     */
    public function doCacheRefresh()
    {
        // Lots of records will take a while to do… Amazon limits us to one
        // request per second
        set_time_limit(0);

        $rows = $this->app['amazon.records']->doLookupASINs();

        foreach ($rows as $row) {
            $this->doLookupASIN($row['asin'], false);

            // Rate limit our refresh
            $this->app['amazon.utils']->requestThrottle();
        }
    }

    /**
     * Lookup a given ASIN via the Amazon API
     *
     * @param string $asin
     *
     * @return array
     */
    private function doAmazonRequest($asin)
    {
        /** @var $lookup \ApaiIO\Operations\Lookup */
        $lookup = new ApaiIOLookup();
        $lookup->setIdType('ASIN')
            ->setItemId($asin)
            ->setSearchIndex(false)
            ->setResponseGroup($this->config['responsegroup']);

        try {
            // Throttle the request, if required
            $this->app['amazon.utils']->requestThrottle();

            // Make request to Amazon
            $response = $this->getApaiIO()->runOperation($lookup);

            // Log it
            $this->app['logger.system']->info("API HTTP request for ASIN: $asin", array('event' => 'amazon'));
        } catch (\Exception $e) {
            $this->app['logger.system']->critical('Amazon API lookup failure: ' . $e->getMessage(), array('event' => 'exception', 'exception' => $e));
        }

        // A valid request may have an error… 'cause Amazon sucks…
        if (isset($response['Items']['Request']['Errors'])) {
            $this->app['amazon.records']->doLogError($response);

            return null;
        }

        $response = $this->app['amazon.utils']->getFormattedArray($response);

        if (isset($response['asin']) && $response['asin'] != '') {
            $this->app['amazon.records']->doCacheASIN($response);
        }

        return $response;
    }

    /**
     * Get/setup ApaiIO object
     *
     * @throws InvalidConfigurationException
     *
     * @return \ApaiIO\ApaiIO
     */
    private function getApaiIO()
    {
        if ($this->apaiIO) {
            return $this->apaiIO;
        }

        if (empty($this->config['country'])) {
            throw new InvalidConfigurationException("Missing Amazon API configuration parameter: 'country'");
        }
        if (empty($this->config['accesskey'])) {
            throw new InvalidConfigurationException("Missing Amazon API configuration parameter: 'accesskey'");
        }
        if (empty($this->config['secretkey'])) {
            throw new InvalidConfigurationException("Missing Amazon API configuration parameter: 'secretkey'");
        }
        if (empty($this->config['associatetag'])) {
            throw new InvalidConfigurationException("Missing Amazon API configuration parameter: 'associatetag'");
        }

        // Initialise customer configuration
        $this->conf = new GenericConfiguration();

        // Set up the PHP API object
        try {
            $this->conf
                ->setCountry($this->config['country'])
                ->setAccessKey($this->config['accesskey'])
                ->setSecretKey($this->config['secretkey'])
                ->setAssociateTag($this->config['associatetag'])
                ->setRequest('\ApaiIO\Request\Soap\Request')
                ->setResponseTransformer('\ApaiIO\ResponseTransformer\ObjectToArray');
        } catch (\Exception $e) {
            $this->app['logger.system']->critical('Amazon API setup failure: ' . $e->getMessage(), array('event' => 'exception', 'exception' => $e));
        }

        // Initialise the API with configuration
        $this->apaiIO = new ApaiIO($this->conf);

        return $this->apaiIO;
    }
}
