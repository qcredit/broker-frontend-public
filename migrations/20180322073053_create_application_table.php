<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateApplicationTable extends AbstractMigration
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
      $table->addColumn('loan_amount', 'decimal', ['precision' => 8, 'scale' => 2])
        ->addColumn('loan_term', 'integer', ['limit' => MysqlAdapter::INT_TINY])
        ->addColumn('first_name', 'string', ['limit' => 90])
        ->addColumn('last_name', 'string', ['limit' => 90])
        ->addColumn('email', 'string', ['limit' => 90])
        ->addColumn('phone', 'string', ['limit' => 60])
        ->addColumn('street', 'string', ['limit' => 90])
        ->addColumn('house_nr', 'string', ['limit' => 8])
        ->addColumn('apartment_nr', 'string', ['limit' => 8])
        ->addColumn('city', 'string')
        ->addColumn('state', 'string')
        ->addColumn('country', 'string', ['limit' => 90])
        ->addColumn('document_nr', 'string', ['limit' => 60])
        ->addColumn('selected_offer', 'integer', ['null' => true])
        ->addColumn('data', 'json', ['null' => true])
        ->addColumn('created_at', 'datetime')
        ->addColumn('accepted_at', 'datetime', ['null' => true])
        ->addColumn('application_hash', 'string', ['limit' => 90]);

      $table->addIndex(['email'], ['name' => 'idx_email'])
        ->addIndex(['phone'], ['name' => 'idx_phone'])
        ->addIndex(['application_hash'], ['name' => 'idx_app_hash'])
        ->save();
    }
}
