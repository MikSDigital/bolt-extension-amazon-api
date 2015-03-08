<?php

namespace Bolt\Extension\Bolt\AmazonApi\Provider;

use Bolt\Extension\Bolt\AmazonApi\Lookup;
use Bolt\Extension\Bolt\AmazonApi\Records;
use Bolt\Extension\Bolt\AmazonApi\Utils;
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
        $app['amazon.lookup'] = $app->share(
            function ($app) {
                $lookup = new Lookup($app);

                return $lookup;
            }
        );

        $app['amazon.records'] = $app->share(
            function ($app) {
                $records = new Records($app);

                return $records;
            }
        );

        $app['amazon.utils'] = $app->share(
            function ($app) {
                $records = new Utils($app);

                return $records;
            }
        );
    }

    public function boot(Application $app)
    {
    }
}
