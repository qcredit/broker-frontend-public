<?php


use Phinx\Seed\AbstractSeed;

class UserSeed extends AbstractSeed
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
          'email' => 'hendrik.uibopuu@aasaglobal.com',
          'access_level' => 1,
          'created_at' => '2018-04-11 10:10:10'
        ],
        [
          'email' => 'hainer.savimaa@aasaglobal.com',
          'access_level' => 1,
          'created_at' => '2018-04-11 10:11:12'
        ]
      ];

      $this->table('user')
        ->insert($data)
        ->save();
    }
}
