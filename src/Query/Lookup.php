<?php

namespace Bolt\Extension\Bolt\AmazonApi\Query;

use ApaiIO\Operations\Lookup as ApaiIOLookup;
use Bolt\Extension\Bolt\AmazonApi\ApiResponse;

/**
 * Amazon query request class
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
     * @return array
     */
    public function doLookupASIN($asin, $useCache = true)
    {
        // Check first in the database for a cached version
        if ($useCache) {
            $response = $this->getRecords()->doLookupASIN($asin);
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

        $rows = $this->getRecords()->doLookupASINs();

        foreach ($rows as $row) {
            $this->doLookupASIN($row['asin'], false);

            // Rate limit our refresh
            $this->getUtils()->requestThrottle();
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
        // Throttle the request, if required
//$this->getUtils()->requestThrottle();

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
            $this->getRecords()->doLogError($response);

            return null;
        }

        if ($response->has('asin') && $response->get('asin') !== '') {
            $this->getRecords()->doCacheASIN($response);
        }

        return $response;
    }
}
