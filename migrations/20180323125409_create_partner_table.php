<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreatePartnerTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
      $table = $this->table('partner');
      $table->addColumn('name', 'string')
        ->addColumn('identifier', 'string', ['limit' => 60])
        ->addColumn('status', 'integer', ['limit' => MysqlAdapter::INT_TINY])
        ->addColumn('commission', 'decimal', ['precision' => 5, 'scale' => 2]);

      $table->addIndex(['identifier'], ['name' => 'idx_identifier'])
        ->save();
    }
}
