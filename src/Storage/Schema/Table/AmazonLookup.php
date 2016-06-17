<?php

namespace Bolt\Extension\Bolt\AmazonApi\Storage\Schema\Table;

use Bolt\Storage\Database\Schema\Table\BaseTable;

/**
 * Amazon lookup table.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class AmazonLookup extends BaseTable
{
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        $this->table->addColumn('asin',             'string', array('length' => 32));
        $this->table->addColumn('type',             'string', array('length' => 32));
        $this->table->addColumn('binding',          'string', array('length' => 32));
        $this->table->addColumn('cached',           'date');
        $this->table->addColumn('author',           'string', array('length' => 128));
        $this->table->addColumn('title',            'text');
        $this->table->addColumn('publisher',        'text');
        $this->table->addColumn('pages',            'integer');
        $this->table->addColumn('price',            'string');
        $this->table->addColumn('image_small_url',  'text');
        $this->table->addColumn('image_medium_url', 'text');
        $this->table->addColumn('image_large_url',  'text');
        $this->table->addColumn('image_local',      'text');
        $this->table->addColumn('detail_url',       'text');
        $this->table->addColumn('review',           'text');        
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addIndex(array('asin'));
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(array('asin'));
    }
}
