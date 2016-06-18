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
        $this->table->addColumn('id',     'integer',    ['autoincrement' => true]);
        $this->table->addColumn('asin',   'string',     ['length' => 32]);
        $this->table->addColumn('item',   'json_array', []);
        $this->table->addColumn('cached', 'datetime',   []);
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addUniqueIndex(['asin']);
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(['id']);
    }
}
