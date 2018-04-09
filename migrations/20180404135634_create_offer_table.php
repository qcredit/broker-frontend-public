<?php
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreateOfferTable extends AbstractMigration
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
      $table = $this->table('offer');
      $table->addColumn('application_id', 'integer')
        ->addColumn('partner_id', 'integer')
        ->addColumn('loan_amount', 'decimal', ['precision' => 8, 'scale' => 2, 'null' => true])
        ->addColumn('loan_term', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => true])
        ->addColumn('interest', 'decimal', ['precision' => 5, 'scale' => 2, 'null' => true])
        ->addColumn('monthly_fee', 'decimal', ['precision' => 8, 'scale' => 2, 'null' => true])
        ->addColumn('rejected_date', 'datetime', ['null' => true])
        ->addColumn('accepted_date', 'datetime', ['null' => true])
        ->addColumn('paid_out_date', 'datetime', ['null' => true])
        ->addColumn('remote_id', 'string')
        ->addColumn('data', 'json', ['null' => true]);


      $table->addForeignKey('application_id', 'application', 'id', [
        'delete' => 'RESTRICT',
        'update' => 'CASCADE',
        'constraint' => 'fk_offer_app'
      ])->addForeignKey('partner_id', 'partner', 'id', [
        'delete' => 'RESTRICT',
        'update' => 'CASCADE',
        'constraint' => 'fk_offer_partner'
      ])->save();
    }
}
