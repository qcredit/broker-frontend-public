<?php


use Phinx\Seed\AbstractSeed;

class PartnerSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
      $data = [
        [
          'name' => 'Aasa',
          'identifier' => 'AASA',
          'status' => 1,
          'commission' => 2
        ]
      ];

      $this->table('partner')
        ->insert($data)
        ->save();
    }
}
