<?php

namespace Bolt\Extension\Bolt\AmazonApi\Query;

use ApaiIO\Operations\Lookup as ApaiIOLookup;
use Bolt\Extension\Bolt\AmazonApi\Response\ApiResponse;
use Bolt\Extension\Bolt\AmazonApi\Storage\Entity;
use Bolt\Extension\Bolt\AmazonApi\Utils;

/**
 * Amazon query request class.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class Lookup extends AbstractQuery
{
    /**
     * Find an ASIN record, either from database cache or Amazon
     *
     * @param string  $asin
     * @param boolean $useCache
     *
     * @return Entity\AmazonLookup
     */
    public function doLookupASIN($asin, $useCache = true)
    {
        $response = false;

        // Check first in the database for a cached version
        if ($useCache) {
            $response = $this->getRecords()->getLookupByASIN($asin);
        }

        if ($response === false) {
            $response = $this->doAmazonRequest($asin);
        }

        return $response;
    }

    /**
     * Re-query Amazon for each cached ASIN and update our cache with any
     * changed information.
     */
    public function doCacheRefresh()
    {
        // Lots of records will take a while to do… Amazon limits us to one
        // request per second
        set_time_limit(0);

        $rows = $this->getRecords()->getLookups();

        /** @var Entity\AmazonLookup $row */
        foreach ($rows as $row) {
            $this->doLookupASIN($row->getAsin(), false);

            // Rate limit our refresh
            Utils::requestThrottle();
        }
    }

    /**
     * Lookup a given ASIN via the Amazon API
     *
     * @param string $asin
     *
     * @throws \RuntimeException
     *
     * @return Entity\AmazonLookup
     */
    private function doAmazonRequest($asin)
    {
        // Throttle the request, if required
        Utils::requestThrottle();

        /** @var $lookup \ApaiIO\Operations\Lookup */
        $lookup = new ApaiIOLookup();
        $lookup->setIdType('ASIN')
            ->setItemId($asin)
            ->setSearchIndex(false)
            ->setResponseGroup($this->config->getResponseGroup())
        ;

        // Make request to Amazon
        /** @var ApiResponse $response */
        $response = $this->getApaiIO()->runOperation($lookup);

        // A valid request may have an error… 'cause Amazon…
        if ($response->isError()) {
            $this->getRecords()->saveLookupError($response);

            return null;
        }

        if ($response->get('asin', null, true) !== '') {
            $this->getRecords()->saveLookup($response);

            return $this->getRecords()->getLookupByASIN($asin);
        }

        throw new \RuntimeException(sprintf('Invalid response from Amazon API for %s', $asin));
    }
}
