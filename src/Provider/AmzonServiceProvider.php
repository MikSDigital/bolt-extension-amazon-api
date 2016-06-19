<?php

namespace Bolt\Extension\Bolt\AmazonApi\Provider;

use Bolt\Extension\Bolt\AmazonApi\Config;
use Bolt\Extension\Bolt\AmazonApi\Query;
use Bolt\Extension\Bolt\AmazonApi\Storage\Entity;
use Bolt\Extension\Bolt\AmazonApi\Storage\Records;
use Pimple as Container;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Provider for Amazon API classes
 *
 * @author    Gawain Lynch <gawain.lynch@gmail.com>
 * @copyright Copyright (c) 2015, Gawain Lynch
 */
class AmzonServiceProvider implements ServiceProviderInterface
{
    /** @var array */
    private $config;
    
    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function register(Application $app)
    {
        $app['amazon.config'] = $app->share(
            function () {
                return new Config($this->config);
            }
        );

        $app['amazon.api'] = $app->share(
            function ($app) {
                return new Container([
                    'lookup'  => $app->share(function () use ($app) { return new Query\Lookup($app['amazon.config'], $app['amazon.records'], $app['guzzle.client']); }),
                    'product' => $app->share(function () use ($app) { return new Query\Product($app['amazon.config'], $app['amazon.records'], $app['guzzle.client']); }),
                ]);
            }
        );

        $app['amazon.repos'] = $app->share(
            function ($app) {
                return new Container([
                    'lookup'        => $app->share(function () use ($app) { return $app['storage']->getRepository(Entity\AmazonLookup::class); }),
                    'lookup_errors' => $app->share(function () use ($app) { return $app['storage']->getRepository(Entity\AmazonLookupErrors::class); }),
                ]);
            }
        );

        $app['amazon.records'] = $app->share(
            function ($app) {
                return new Records($app['amazon.config'], $app['amazon.repos']);
            }
        );
    }

    public function boot(Application $app)
    {
    }
}
