<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use Bolt\Extension\Bolt\AmazonApi\Storage\Entity;
use Bolt\Extension\Bolt\AmazonApi\Storage\Repository;
use Bolt\Extension\Bolt\AmazonApi\Storage\Schema\Table;
use Bolt\Events\CronEvent;
use Bolt\Events\CronEvents;
use Bolt\Extension\DatabaseSchemaTrait;
use Bolt\Extension\SimpleExtension;
use Bolt\Extension\StorageTrait;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Amazon product API for Bolt
 *
 * @author    Gawain Lynch <gawain.lynch@gmail.com>
 */
class AmazonApiExtension extends SimpleExtension
{
    use DatabaseSchemaTrait;
    use StorageTrait;

    /**
     * {@inheritdoc}
     */
    public function getServiceProviders()
    {
        return [
            $this,
            new Provider\AmzonServiceProvider($this->getConfig())
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function subscribe(EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addListener(CronEvents::CRON_MONTHLY, array($this, 'cronMonthly'));
    }

    /**
     * @param \Bolt\Events\CronEvent $event
     */
    public function cronMonthly(CronEvent $event)
    {
        $app = $this->getContainer();
        // Lookup and update db entries
        $event->output->writeln('<comment>Starting Amazon cache refresh</comment>');

        /** @var Query\Lookup $lookup */
        $lookup = $app['amazon.api']['lookup'];
        $lookup->doCacheRefresh();
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

    /**
     * {@inheritdoc}
     */
    protected function registerExtensionTables()
    {
        return [
            'amazon_lookup'        => Table\AmazonLookup::class,
            'amazon_lookup_errors' => Table\AmazonLookupErrors::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerRepositoryMappings()
    {
        return [
            'amazon_lookup'        => [Entity\AmazonLookup::class       => Repository\AmazonLookup::class],
            'amazon_lookup_errors' => [Entity\AmazonLookupErrors::class => Repository\AmazonLookupErrors::class],
        ];
    }
}
