<?php

namespace Bolt\Extension\Bolt\AmazonApi\Storage\Schema\Table;

use Bolt\Storage\Database\Schema\Table\BaseTable;

/**
 * Amazon lookup errors table.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class AmazonLookupErrors extends BaseTable
{
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        $this->table->addColumn('id',    'integer', array('autoincrement' => true));
        $this->table->addColumn('date',  'datetime');
        $this->table->addColumn('asin',  'string', array('length' => 32, 'notnull' => false));
        $this->table->addColumn('code',  'text');
        $this->table->addColumn('error', 'text');
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
        $this->table->setPrimaryKey(array('id'));
    }
}
