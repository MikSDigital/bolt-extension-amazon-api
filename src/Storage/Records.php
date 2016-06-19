<?php

namespace Bolt\Extension\Bolt\AmazonApi\Storage;

use Bolt\Extension\Bolt\AmazonApi\AbstractApiResponse;
use Bolt\Extension\Bolt\AmazonApi\ApiResponseElement;
use Bolt\Extension\Bolt\AmazonApi\Config;
use Carbon\Carbon;
use Pimple as Container;

/**
 * Records class.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class Records
{
    /** @var Config */
    private $config;
    /** @var Container */
    private $repos;

    /**
     * Constructor.
     *
     * @param Config    $config
     * @param Container $repos
     */
    public function __construct(Config $config, Container $repos)
    {
        $this->repos = $repos;
        $this->config = $config;
    }

    /**
     * Check database for a pre-stored ASIN
     *
     * @param string $asin An Amazon ASIN
     *
     * @return Entity\AmazonLookup
     */
    public function getLookupByASIN($asin)
    {
        return $this->getLookupRepo()->getLookupByASIN($asin);
    }

    /**
     * Return all ASIN records
     *
     * @return Entity\AmazonLookup[]
     */
    public function getLookups()
    {
        return $this->getLookupRepo()->getLookups();
    }

    /**
     * Cache the ASIN lookup
     *
     * @param AbstractApiResponse $response Formatted response from Amazon
     */
    public function saveLookup(AbstractApiResponse $response)
    {
        /** @var ApiResponseElement $item */
        $item = $response->get('items')->get('item');
        $entity = new Entity\AmazonLookup([
            'asin'   => $item->get('asin'),
            'item'   => $item->all(),
            'cached' => Carbon::now(),
        ]);

        $this->getLookupRepo()->save($entity);
    }

    /**
     * Log and error received from an Amazon query
     *
     * @param AbstractApiResponse $response
     */
    public function saveLookupError(AbstractApiResponse $response)
    {
        /** @var ApiResponseElement $data */
        $data = $response->get('items')->get('request');
        $errorEntity = new Entity\AmazonLookupErrors([
            'date'  => Carbon::now(),
            'asin'  => $data->get('itemlookuprequest')->get('itemid'),
            'code'  => $data->get('errors')->get('error')->get('code'),
            'error' => $data->get('errors')->get('error')->get('message'),
        ]);
        $this->getLookupErrorRepo()->save($errorEntity);
    }

    /**
     * @return Repository\AmazonLookup
     */
    protected function getLookupRepo()
    {
        return $this->repos['lookup'];
    }

    /**
     * @return Repository\AmazonLookupErrors
     */
    protected function getLookupErrorRepo()
    {
        return $this->repos['lookup_errors'];
    }
}
