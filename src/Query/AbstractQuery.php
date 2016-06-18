<?php

namespace Bolt\Extension\Bolt\AmazonApi\Query;

use ApaiIO\ApaiIO;
use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Request\GuzzleRequest;
use Bolt\Extension\Bolt\AmazonApi\Config;
use Bolt\Extension\Bolt\AmazonApi\Exception\InvalidConfigurationException;
use Bolt\Extension\Bolt\AmazonApi\Records;
use Bolt\Extension\Bolt\AmazonApi\ResponseTransformer\XmlToParameterBagObject;
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

    /** @var \ApaiIO\ApaiIO */
    private $apaiIO;

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

    /**
     * Get/setup ApaiIO object
     *
     * @throws InvalidConfigurationException
     *
     * @return \ApaiIO\ApaiIO
     */
    protected function getApaiIO()
    {
        if ($this->apaiIO) {
            return $this->apaiIO;
        }

        if ($this->config->getCountry() === null) {
            throw new InvalidConfigurationException("Missing Amazon API configuration parameter: 'country'");
        }
        if ($this->config->getAccessKey() === null) {
            throw new InvalidConfigurationException("Missing Amazon API configuration parameter: 'accesskey'");
        }
        if ($this->config->getSecretKey() === null) {
            throw new InvalidConfigurationException("Missing Amazon API configuration parameter: 'secretkey'");
        }
        if ($this->config->getAssociateTag() === null) {
            throw new InvalidConfigurationException("Missing Amazon API configuration parameter: 'associatetag'");
        }

        // Initialise customer configuration
        $conf = new GenericConfiguration();

        // Set up the PHP API object
        $conf
            ->setCountry($this->config->getCountry())
            ->setAccessKey($this->config->getAccessKey())
            ->setSecretKey($this->config->getSecretKey())
            ->setAssociateTag($this->config->getAssociateTag())
            ->setRequest(new GuzzleRequest($this->getGuzzle()))
            ->setResponseTransformer(new XmlToParameterBagObject())
        ;

        // Initialise the API with configuration
        $this->apaiIO = new ApaiIO($conf);

        return $this->apaiIO;
    }
}
