<?php

namespace Bolt\Extension\Bolt\AmazonApi\Provider;

use Bolt\Extension\Bolt\AmazonApi\Lookup;
use Bolt\Extension\Bolt\AmazonApi\Records;
use Bolt\Extension\Bolt\AmazonApi\Utils;
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
    public function register(Application $app)
    {
        $app['amazon.api'] = $app->share(
            function ($app) {
                return new Container([
                    'lookup'  => $app->share(function () use ($app) { return $lookup = new Lookup($app); }),
                    'records' => $app->share(function () use ($app) { return $lookup = new Records($app); }),
                    'utils'   => $app->share(function () use ($app) { return $lookup = new Utils($app); }),
                ]);
            }
        );
    }

    public function boot(Application $app)
    {
    }
}
