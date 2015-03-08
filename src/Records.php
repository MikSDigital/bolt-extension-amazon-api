<?php

namespace Bolt\Extension\Bolt\AmazonApi;

use Doctrine\DBAL\Schema\Schema;
use Silex\Application;

/**
 *
 * @author    Gawain Lynch <gawain.lynch@gmail.com>
 * @copyright Copyright (c) 2015, Gawain Lynch
 */
class Records
{
    /** @var Application */
    private $app;

    /** @var array */
    private $config;

    /** @var Utils */
    private $utils;

    /** @var string Amazon book table */
    private $table_name;

    /** @var string Error logging table */
    private $error_table_name;

    /**
     * @param \Bolt\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = $this->app['extensions.' . Extension::NAME]->config;

        $prefix = $this->app['config']->get('general/database/prefix', 'bolt_');
        $this->table_name = $prefix . 'amazon_lookup';
        $this->error_table_name = $prefix . 'amazon_lookup_errors';
    }

    /**
     * Check database for a pre-stored ASIN
     *
     * @param string $asin An Amazon ASIN
     *
     * @return array
     */
    public function doLookupASIN($asin)
    {
        // Set up query
        $query = $this->app['db']->createQueryBuilder()
            ->select('*')
            ->from($this->table_name)
            ->where('asin = :asin')
            ->setMaxResults(1)
            ->setParameters(array(':asin' => $asin));

        try {
            $record = $query->execute()->fetchAll(\PDO::FETCH_ASSOC);
            return $record[0];
        } catch (\Exception $e) {
            $this->app['logger.system']->critical('Amazon cache lookup failure: ' . $e->getMessage(), array('event' => 'exception', 'exception' => $e));
        }
    }

    /**
     * Return all ASIN records
     *
     * @return array
     */
    public function doLookupASINs()
    {
        $query = $this->app['db']->createQueryBuilder()
            ->select('*')
            ->from($this->table_name);

        return $query->fetchAll();
    }

    /**
     * Cache the ASIN lookup
     *
     * @param array $response Formatted resonse from Amazon
     *
     * @return NULL
     */
    public function doCacheASIN(array $response)
    {
/*
        if ($response['image_local'] && !file_exists($response['image_local'])) {
            if ($this->app['amazon.utils']->remoteExists($response['image_large_url'])) {
                $this->app['amazon.utils']->downloadFile($response['image_large_url'], $this->app['paths']['filespath'] . '/' . $response['image_local']);
            } elseif ($this->app['amazon.utils']->remoteExists($response['image_medium_url'])) {
                $this->app['amazon.utils']->downloadFile($response['image_medium_url'], $this->app['paths']['filespath'] . '/' . $response['image_local']);
            } elseif ($this->app['amazon.utils']->remoteExists($response['image_small_url'])) {
                $this->app['amazon.utils']->downloadFile($response['image_small_url'], $this->app['paths']['filespath'] . '/' . $response['image_local']);
            }
        }
*/
        $this->app['amazon.utils']->downloadFile($response['image_large_url'], $this->app['paths']['filespath'] . '/' . $response['image_local']);

        // Shorten title
        if (strpos($response['title'], '(') !== false) {
            $response['title'] = trim(strstr($response['title'], '(', true));
        }

        // Attempt to get the extising record, if exists we update
        $cached = $this->doLookupASIN($response['asin']);

        /*
         * If we got a cached response, make sure that any existing values take
         * priority over blank values
         */
        if (! empty($cached)) {
            foreach ((array) $cached as $key => $value) {
                $response[$key] = empty($response[$key]) ? $cached[$key] : $response[$key];
            }
        }

        $data = array(
            'asin'             => $response['asin'],
            'cached'           => date('Y-m-d', time()),
            'author'           => $response['author'],
            'title'            => $response['title'],
            'type'             => $response['type'],
            'binding'          => $response['binding'],
            'publisher'        => $response['publisher'],
            'pages'            => $response['pages'],
            'price'            => $response['price'],
            'image_small_url'  => $response['image_small_url'],
            'image_medium_url' => $response['image_medium_url'],
            'image_large_url'  => $response['image_large_url'],
            'image_local'      => $response['image_local'],
            'detail_url'       => $response['detail_url'],
            'review'           => $response['review']
        );

        if (empty($cached)) {
            $this->app['db']->insert($this->table_name, $data);
        } else {
            $this->app['db']->update($this->table_name,
                $data,
                array('asin' => $response['asin'])
            );
        }
    }

    /**
     * Log and error received from an Amazon query
     *
     * @param array $response
     */
    public function doLogError(array $response)
    {
        $data = $response['Items']['Request'];

        // Log to Bolt's system log
        $this->app['logger.system']->critical('Amazon API request failure: ' . $data['Errors']['Error']['Message'], array('event' => 'amazon'));

        // Write the log detail to the database
        $this->app['db']->insert($this->error_table_name,
            array(
                'date'  => date('Y-m-d H:i:s', time()),
                'asin'  => $data['ItemLookupRequest']['ItemId'],
                'code'  => $data['Errors']['Error']['Code'],
                'error' => $data['Errors']['Error']['Message']
            )
        );
    }

    /**
     * Register, setup and index our database table
     */
    public function dbCheck()
    {
        $me = $this;

        // Lookup table
        $this->app['integritychecker']->registerExtensionTable(
            function (Schema $schema) use ($me) {
                // Define table
                $table = $schema->createTable($me->table_name);

                $table->addColumn('asin',             'string', array('length' => 32));
                $table->addColumn('type',             'string', array('length' => 32));
                $table->addColumn('binding',          'string', array('length' => 32));
                $table->addColumn('cached',           'date');
                $table->addColumn('author',           'string', array('length' => 128));
                $table->addColumn('title',            'text');
                $table->addColumn('publisher',        'text');
                $table->addColumn('pages',            'integer');
                $table->addColumn('price',            'string');
                $table->addColumn('image_small_url',  'text');
                $table->addColumn('image_medium_url', 'text');
                $table->addColumn('image_large_url',  'text');
                $table->addColumn('image_local',      'text');
                $table->addColumn('detail_url',       'text');
                $table->addColumn('review',           'text');

                // Primary key
                $table->setPrimaryKey(array('asin'));

                // Index column(s)
                $table->addIndex(array('asin'));

                return $table;
            });

            // Error log table
            $this->app['integritychecker']->registerExtensionTable(
                function (Schema $schema) use ($me) {
                    // Define table
                    $table = $schema->createTable($me->error_table_name);

                    $table->addColumn('id',    'integer', array('autoincrement' => true));
                    $table->addColumn('date',  'datetime');
                    $table->addColumn('asin',  'string', array('length' => 32, 'notnull' => false));
                    $table->addColumn('code',  'text');
                    $table->addColumn('error', 'text');

                    // Primary key
                    $table->setPrimaryKey(array('id'));

                    // Index column(s)
                    $table->addIndex(array('asin'));

                    return $table;
                });
    }
}
