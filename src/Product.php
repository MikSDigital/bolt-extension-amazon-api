<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

/**
 * Product request object
 *
 * @author    Gawain Lynch <gawain.lynch@gmail.com>
 * @copyright Copyright (c) 2015, Gawain Lynch
 */
class Product
{
    /** @var Application */
    private $app;

    /** @var array */
    private $config;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        // Get some inheritance happening
        $this->app = $app;

        $this->config = $this->app['extensions.' . Extension::NAME]->config;
    }

}
