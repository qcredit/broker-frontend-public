<?php


use Phinx\Seed\AbstractSeed;

class OfferSeed extends AbstractSeed
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
          'application_id' => 1,
          'partner_id' => 1,
          'rejected_date' => '2018-04-04 10:10:10',
          'remote_id' => '31896319',
          'data' => '{"token": "c57a6872-1c42-430e-973a-bd3a7538b60f", "status": "Rejected", "esignUrl": null, "contractUrl": null, "loanDetails": null, "rejectReason": "DoubleApplication", "acceptancePageUrl": null}'
        ]
      ];

      $this->table('offer')
        ->insert($data)
        ->save();
    }
}
