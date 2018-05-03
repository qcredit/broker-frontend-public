<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class RemoveNotNullAppColumns extends AbstractMigration
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
      $table = $this->table('application');
      $table->changeColumn('loan_amount', 'decimal', ['precision' => 8, 'scale' => 2, 'null' => true])
        ->changeColumn('loan_term', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => true])
        ->changeColumn('first_name', 'string', ['limit' => 90, 'null' => true])
        ->changeColumn('last_name', 'string', ['limit' => 90, 'null' => true])
        ->changeColumn('email', 'string', ['limit' => 90, 'null' => true])
        ->changeColumn('phone', 'string', ['limit' => 60, 'null' => true])
        ->changeColumn('document_nr', 'string', ['limit' => 60, 'null' => true])
        ->changeColumn('selected_offer', 'integer', ['null' => true])
        ->changeColumn('data', 'json', ['null' => true])
        ->changeColumn('created_at', 'datetime')
        ->changeColumn('accepted_at', 'datetime', ['null' => true])
        ->changeColumn('application_hash', 'string', ['limit' => 90]);
    }
}
