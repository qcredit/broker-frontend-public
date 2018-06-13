<?php


use Phinx\Migration\AbstractMigration;

class AddGdprColumns extends AbstractMigration
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

      $table->addColumn('gdpr_1', 'boolean', ['default' => false])
        ->addColumn('gdpr_2', 'boolean', ['default' => false])
        ->addColumn('email_consent', 'boolean', ['default' => false])
        ->addColumn('phone_consent', 'boolean', ['default' => false])
        ->save();
    }
}
