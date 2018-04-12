<?php


use Phinx\Migration\AbstractMigration;

class CreateUserTable extends AbstractMigration
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
      $table = $this->table('user');
      $table->addColumn('email', 'string')
        ->addColumn('access_level', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY])
        ->addColumn('auth_key', 'string', ['null' => true])
        ->addColumn('created_at', 'datetime');

      $table->addIndex(['email'], ['name' => 'idx_email', 'unique' => true])
        ->save();
    }
}
