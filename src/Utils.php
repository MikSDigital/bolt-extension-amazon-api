<?php

namespace Bolt\Extension\Bolt\AmazonApi;

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
     * Get the opposing formats ASIN.
     *
     * @param array  $response
     * @param string $format   Either 'physical' or 'digital'
     *
     * @return string|null
     */
    public static function getAltVersionASIN($response, $format)
    {
        if (!isset($response['alternateversions'])) {
            return null;
        }

        foreach ($response['alternateversions'] as $alt) {
            if ($format === 'physical' && ($alt['Binding'] === 'Hardcover' || $alt['Binding'] === 'Paperback')) {
                return $alt['ASIN'];
            } elseif ($format === 'digital' && $alt['Binding'] === 'Kindle Edition') {
                return $alt['ASIN'];
            }
        }

        return null;
    }
}
