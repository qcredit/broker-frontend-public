<?php


use Phinx\Seed\AbstractSeed;

class ApplicationSeed extends AbstractSeed
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
          'loan_amount' => 4000,
          'loan_term' => 24,
          'first_name' => 'Koit',
          'last_name' => 'Toome',
          'email' => 'koit@toome.ee',
          'phone' => '23343424234',
          'document_nr' => 'AA2343443',
          'data' => '{"pin": "92090700966", "zip": "66-121", "city": "Warsawa", "trade": "unknown", "street": "Koidu", "houseNr": "20", "position": "Wont tell", "accountNr": "11232345678901234569415835", "yearSince": 2006, "monthSince": 6, "accountType": "Personal", "apartmentNr": "1", "netPerMonth": 3200, "currentStudy": "TÜ", "employerName": "Wasras asd", "payoutMethod": "Account", "propertyType": "House", "accountHolder": "Koit Toome", "educationType": "MSc", "loanPurposeType": "Car", "mobilePhoneType": "PrePaid", "residentialType": "Own", "incomeSourceType": "Employed", "maritalStatusType": "Single"}',
          'created_at' => '2018-04-04 11:12:13',
          'application_hash' => '9c3635d262f8e9d11c790857078dad426b71e92964dc794a'
        ]
      ];

      $this->table('application')
        ->insert($data)
        ->save();
    }
}
