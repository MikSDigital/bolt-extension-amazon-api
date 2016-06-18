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
        $this->table->addColumn('id',               'integer',    ['autoincrement' => true]);
        $this->table->addColumn('asin',             'string',     ['length' => 32]);
        $this->table->addColumn('type',             'string',     ['length' => 32]);
        $this->table->addColumn('binding',          'string',     ['length' => 32]);
        $this->table->addColumn('cached',           'date',       []);
        $this->table->addColumn('author',           'string',     ['length' => 128]);
        $this->table->addColumn('title',            'text',       []);
        $this->table->addColumn('publisher',        'text',       []);
        $this->table->addColumn('pages',            'integer',    []);
        $this->table->addColumn('price',            'json_array', []);
        $this->table->addColumn('image_small_url',  'json_array', []);
        $this->table->addColumn('image_medium_url', 'json_array', []);
        $this->table->addColumn('image_large_url',  'json_array', []);
        $this->table->addColumn('image_local',      'text',       []);
        $this->table->addColumn('detail_url',       'text',       []);
        $this->table->addColumn('review',           'text',       []);
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addIndex(['asin']);
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(['asin']);
    }
}
