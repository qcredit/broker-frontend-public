<?php


use Phinx\Migration\AbstractMigration;

class AddNewPartnerColumns extends AbstractMigration
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

      $table->addColumn('email', 'string', ['null' => true])
        ->addColumn('website', 'string', ['null' => true])
        ->addColumn('api_enabled', 'boolean', ['default' => false])
        ->addColumn('api_live_url', 'string', ['null' => true])
        ->addColumn('api_test_url', 'string', ['null' => true])
        ->addColumn('remote_username', 'string', ['null' => true])
        ->addColumn('remote_password', 'string', ['null' => true])
        ->addColumn('local_username', 'string', ['null' => true])
        ->addColumn('local_password', 'string', ['null' => true])
        ->addColumn('authorization_type', 'string', ['null' => true])
        ->addColumn('logo_url', 'string', ['null' => true])
        ->save();
    }
}
