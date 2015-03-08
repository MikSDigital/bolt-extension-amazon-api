<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use Bolt\Events\CronEvent;
use Bolt\Events\CronEvents;

/**
 * Amazon product API for Bolt
 *
 * @author    Gawain Lynch <gawain.lynch@gmail.com>
 * @copyright Copyright (c) 2015, Gawain Lynch
 */
class Extension extends \Bolt\BaseExtension
{
    /** @var Extension name */
    const NAME = 'AmazonApi';

    public function getName()
    {
        return Extension::NAME;
    }

    public function initialize()
    {
        /*
         * Providers
         */
        $this->app->register(new Provider\AmzonServiceProvider());

        /*
         * Backend
         */
        if ($this->app['config']->getWhichEnd() === 'backend') {
            $this->app['amazon.records']->dbCheck();
        }

        /*
         * Frontend
         */
        if ($this->app['config']->getWhichEnd() === 'frontend') {
        }

        /*
         * Scheduled cron listener
         */
        $this->app['dispatcher']->addListener(CronEvents::CRON_MONTHLY, array($this, 'cronMonthly'));
    }

    /**
     * @param \Bolt\Events\CronEvent $event
     */
    public function cronMonthly(CronEvent $event)
    {
        // Lookup and update db entries
        $event->output->writeln('<comment>Starting Amazon cache refresh</comment>');

        $this->app['amazon.lookup']->doCacheRefresh();
        $event->output->writeln('<comment>Finished Amazon cache refresh</comment>');
    }

    /**
     * Set the required response group(s) if we end up doing the query
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return array(
            'responsegroup' => array(
                'Medium',
                'Images',
                'AlternateVersions'
            ),
            'country'      => 'com',
            'accesskey'    => '',
            'secretkey'    => '',
            'associatetag' => '',
            'user_agent'   => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36'
        );
    }
}
