<?php

namespace Bolt\Extension\Bolt\AmazonApi\Query;

use Bolt\Extension\Bolt\AmazonApi\Config;
use Bolt\Extension\Bolt\AmazonApi\Records;
use GuzzleHttp\Client;

/**
 * Abstract amazon query class.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class AbstractQuery
{
    /** @var Config */
    protected $config;
    /** @var Records */
    protected $records;
    /** @var Client */
    protected $guzzle;

    /**
     * Constructor.
     *
     * @param Config  $config
     * @param Records $records
     * @param Client  $guzzle
     */
    public function __construct(Config $config, Records $records, Client $guzzle)
    {
        $this->config = $config;
        $this->records = $records;
        $this->guzzle = $guzzle;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Records
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * @return Client
     */
    public function getGuzzle()
    {
        return $this->guzzle;
    }
}
