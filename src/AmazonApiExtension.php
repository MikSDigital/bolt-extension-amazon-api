<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use Bolt\Events\CronEvent;
use Bolt\Events\CronEvents;
use Bolt\Extension\Bolt\AmazonApi\Storage\Entity;
use Bolt\Extension\Bolt\AmazonApi\Storage\Records;
use Bolt\Extension\Bolt\AmazonApi\Storage\Repository;
use Bolt\Extension\Bolt\AmazonApi\Storage\Schema\Table;
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

    public function getDisplayName()
    {
        return 'Amazon API';
    }

    /**
     * {@inheritdoc}
     */
    protected function registerServices(Application $app)
    {
        $this->extendDatabaseSchemaServices();
        $this->extendRepositoryMapping();
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceProviders()
    {
        return [
            $this,
            new Provider\AmzonServiceProvider($this->getConfig()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function subscribe(EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addListener(CronEvents::CRON_MONTHLY, [$this, 'cronMonthly']);
    }

    /**
     * @param \Bolt\Events\CronEvent $event
     */
    public function cronMonthly(CronEvent $event)
    {
        $app = $this->getContainer();
        // Lookup and update db entries
        $event->output->writeln('<comment>Starting Amazon cache refresh</comment>');

        // Lots of records will take a while to do… Amazon limits us to one
        // request per second
        set_time_limit(0);

        /** @var Query\Lookup $lookup */
        $lookup = $app['amazon.api']['lookup'];
        /** @var Records $records */
        $records = $app['amazon.records'];
        $rows = $records->getLookups();

        /** @var Entity\AmazonLookup $row */
        foreach ($rows as $row) {
            // Rate limit our refresh
            Utils::requestThrottle();

            $lookup->getItemByAsin($row->getAsin(), false);
        }

        $event->output->writeln('<comment>Finished Amazon cache refresh</comment>');
    }

    /**
     * Set the required response group(s) if we end up doing the query
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return [
            'responsegroup' => [
                'Medium',
                'Images',
                'AlternateVersions',
            ],
            'country'      => 'com',
            'accesskey'    => null,
            'secretkey'    => null,
            'associatetag' => null,
            'user_agent'   => null,
        ];
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
