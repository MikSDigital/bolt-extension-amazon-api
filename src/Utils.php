<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use Bolt\Extension\Bolt\AmazonApi\Item\Book;
use Bolt\Extension\Bolt\AmazonApi\Storage\Entity;

/**
 * Utility class.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class Utils
{
    /** @var integer */
    private static $ticktock;

    /**
     * Ensure we don't fire more than once per second.
     */
    public static function requestThrottle()
    {
        if (static::$ticktock === null) {
            static::$ticktock = microtime(true);
        }

        $since = microtime(true) - static::$ticktock;
        if ($since < 1) {
            // Sleep for a second
            $snooze = 1000000 * (1 - $since);
            usleep($snooze);
        }

        // Update our timer
        static::$ticktock = microtime(true);
    }

    /**
     * Get the opposing format's ASIN.
     *
     * @param Entity\AmazonLookup $lookup
     * @param string              $format Either 'physical' or 'digital'
     *
     * @return null|string
     */
    public static function getAltVersionASIN(Entity\AmazonLookup $lookup, $format)
    {
        /** @var Book $item */
        $item = $lookup->getItem();
        $altVersion = $item->getAlternateVersions();

        if ($format === 'physical' && ($altVersion->getBinding() === 'Hardcover' || $altVersion->getBinding() === 'Paperback')) {
            return $altVersion->getAsin();
        } elseif ($format === 'digital' && $altVersion->getBinding() === 'Kindle Edition') {
            return $altVersion->getAsin();
        }

        return null;
    }
}
