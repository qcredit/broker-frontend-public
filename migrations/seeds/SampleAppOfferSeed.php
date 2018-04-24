<?php


use Phinx\Seed\AbstractSeed;

class SampleAppOfferSeed extends AbstractSeed
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
      $appData = [
        [
          'loan_amount' => 12000,
          'loan_term' => 36,
          'first_name' => 'Poeesia',
          'last_name' => 'Komissarova',
          'email' => 'poeesia@komissarova.ee',
          'phone' => '5171231',
          'document_nr' => 'AUH611715',
          'data' => '{"pin": "92090700966", "zip": "66-109", "city": "Warsawa", "trade": "unknown", "street": "Elm street", "houseNr": "23", "position": "Cleaning lady", "accountNr": "11232345678901234569415835", "yearSince": 2010, "monthSince": 8, "accountType": "Personal", "apartmentNr": " ", "netPerMonth": 6200, "currentStudy": "", "employerName": "Facebook Inc", "payoutMethod": "Account", "propertyType": "House", "accountHolder": "Poeesia Komissarova", "educationType": "MSc", "loanPurposeType": "Bills", "mobilePhoneType": "PrePaid", "residentialType": "Own", "incomeSourceType": "Employed", "maritalStatusType": "Single"}',
          'created_at' => '2018-04-09 10:46:55',
          'application_hash' => 'cb35f8b6f2ea0b4f49cbf28693f666e82053fb0d64045e43'
        ],
        [
          'loan_amount' => 12000,
          'loan_term' => 36,
          'first_name' => 'Poeesia',
          'last_name' => 'Komissarova',
          'email' => 'poeesia@komissarova.ee',
          'phone' => '51712312',
          'document_nr' => 'ASJ955301',
          'data' => '{"pin": "90112701928", "zip": "66-109", "city": "Warsawa", "trade": "unknown", "street": "Elm street", "houseNr": "32", "position": "Cleaning lady", "accountNr": "32132345678901234569415835", "yearSince": 2010, "monthSince": 8, "accountType": "Personal", "apartmentNr": "22", "netPerMonth": 6200, "currentStudy": "", "employerName": "Facebook Inc", "payoutMethod": "Account", "propertyType": "House", "accountHolder": "Poeesia Komissarova", "educationType": "MSc", "loanPurposeType": "Bills", "mobilePhoneType": "PrePaid", "residentialType": "Own", "incomeSourceType": "Employed", "maritalStatusType": "Single"}',
          'created_at' => '2018-04-09 10:51:26',
          'application_hash' => '9fb656fb462dbf94460cf15420e2ee656bec242f6a221dbf'
        ],
        [
          'loan_amount' => 3000,
          'loan_term' => 24,
          'first_name' => 'Anu',
          'last_name' => 'Saagim',
          'email' => 'anu@saagim.ee',
          'phone' => '5673212',
          'document_nr' => 'ANP179041',
          'data' => '{"pin": "56070615011", "zip": "66-666", "city": "Warsawa", "trade": "unknown", "street": "Final street", "houseNr": "69", "position": "Cleaning lady", "accountNr": "32132345678901234569415123", "yearSince": 2012, "monthSince": 1, "accountType": "Personal", "apartmentNr": "69", "netPerMonth": 4200, "currentStudy": "Eluülikool", "employerName": "Google Inc", "payoutMethod": "Account", "propertyType": "Apartment", "accountHolder": "Anu Saagim", "educationType": "MSc", "loanPurposeType": "Bills", "mobilePhoneType": "PrePaid", "residentialType": "Own", "incomeSourceType": "Employed", "maritalStatusType": "Single"}',
          'created_at' => '2018-04-09 10:54:52',
          'application_hash' => '8a0da0fca9a6f20f2e1cfa7b4592dd5f7200cab7ea23407f'
        ],
        [
          'loan_amount' => 3000,
          'loan_term' => 24,
          'first_name' => 'Włodzimierz',
          'last_name' => 'Kozłowski',
          'email' => 'anu@saagim.ee',
          'phone' => '5673212',
          'document_nr' => 'ANP179041',
          'data' => '{"pin": "56070615011", "zip": "66-666", "city": "Warsawa", "trade": "unknown", "street": "Final street", "houseNr": "69", "position": "Cleaning lady", "accountNr": "32132345678901234569415123", "yearSince": 2012, "monthSince": 1, "accountType": "Personal", "apartmentNr": "69", "netPerMonth": 4200, "currentStudy": "Eluülikool", "employerName": "Google Inc", "payoutMethod": "Account", "propertyType": "Apartment", "accountHolder": "Anu Saagim", "educationType": "MSc", "loanPurposeType": "Bills", "mobilePhoneType": "PrePaid", "residentialType": "Own", "incomeSourceType": "Employed", "maritalStatusType": "Single"}',
          'created_at' => '2018-04-09 10:56:11',
          'application_hash' => 'a0c67afff53f02f6c2c2f30840d97463f2baa5936d2c9e6c'
        ],
        [
          'loan_amount' => 3900,
          'loan_term' => 24,
          'first_name' => 'Pawel',
          'last_name' => 'Kryński',
          'email' => 'pawel@toruloru.ee',
          'phone' => '5227835',
          'document_nr' => 'CCU607692',
          'data' => '{"pin": "88062917333", "zip": "17-315", "city": "Grodzisk", "trade": "unknown", "street": "Krynki-Sobole", "houseNr": "43", "position": "Cleaning man", "accountNr": "32132345678901234569415123", "yearSince": 2013, "monthSince": 1, "accountType": "Personal", "apartmentNr": "69", "netPerMonth": 4400, "currentStudy": "", "employerName": "European Parliament", "payoutMethod": "Account", "propertyType": "Apartment", "accountHolder": "Anu Saagim", "educationType": "MSc", "loanPurposeType": "Bills", "mobilePhoneType": "PrePaid", "residentialType": "Own", "incomeSourceType": "Employed", "maritalStatusType": "Single"}',
          'created_at' => '2018-04-09 10:59:33',
          'application_hash' => 'e59ca1be6755bbd98b3f2fd125b18f8c23981381d600db42'
        ],
        [
          'loan_amount' => 3100,
          'loan_term' => 24,
          'first_name' => 'Pawel',
          'last_name' => 'Kryński',
          'email' => 'pawel@toruloru.ee',
          'phone' => '5227835',
          'document_nr' => 'CCU607692',
          'data' => '{"pin": "88062917333", "zip": "17-315", "city": "Grodzisk", "trade": "unknown", "street": "Krynki-Sobole", "houseNr": "43", "position": "Cleaning man", "accountNr": "32132345678901234569415123", "yearSince": 2013, "monthSince": 1, "accountType": "Personal", "apartmentNr": "69", "netPerMonth": 4400, "currentStudy": "", "employerName": "European Parliament", "payoutMethod": "Account", "propertyType": "Apartment", "accountHolder": "Anu Saagim", "educationType": "MSc", "loanPurposeType": "Bills", "mobilePhoneType": "PrePaid", "residentialType": "Own", "incomeSourceType": "Employed", "maritalStatusType": "Single"}',
          'created_at' => '2018-04-09 11:00:22',
          'application_hash' => 'd10c727dbf8100381097ee5be82ef978e31a08c4b8121612'
        ],
        [
          'loan_amount' => 2100,
          'loan_term' => 18,
          'first_name' => 'Adam',
          'last_name' => 'Barański',
          'email' => 'brak@asakredyt.pl',
          'phone' => '+48739050381',
          'document_nr' => 'CCY014054',
          'data' => '{"pin": "71121513234", "zip": "44-285", "city": "Kornowac", "trade": "unknown", "street": "Wolności", "houseNr": "60", "position": "Cleaning man", "accountNr": "32132345678901234569415123", "yearSince": 2013, "monthSince": 1, "accountType": "Personal", "apartmentNr": " ", "netPerMonth": 4400, "currentStudy": "", "employerName": "European Parliament", "payoutMethod": "Account", "propertyType": "Apartment", "accountHolder": "Anu Saagim", "educationType": "MSc", "loanPurposeType": "Bills", "mobilePhoneType": "PrePaid", "residentialType": "Own", "incomeSourceType": "Employed", "maritalStatusType": "Single"}',
          'created_at' => '2018-04-09 11:03:34',
          'application_hash' => '984d639cec76cd9c73b46ef5d0ac539fb4563226f2ef35e0'
        ],
        [
          'loan_amount' => 2100,
          'loan_term' => 18,
          'first_name' => 'Adam',
          'last_name' => 'Barański',
          'email' => 'brak@asakredyt.pl',
          'phone' => '+48739050381',
          'document_nr' => 'CCY014054',
          'data' => '{"pin": "71121513234", "zip": "44-285", "city": "Kornowac", "trade": "unknown", "street": "Wolności", "houseNr": "60", "position": "Cleaning man", "accountNr": "32132345678901234569415123", "yearSince": 2013, "monthSince": 1, "accountType": "Personal", "apartmentNr": " ", "netPerMonth": 4400, "currentStudy": "", "employerName": "European Parliament", "payoutMethod": "Account", "propertyType": "Apartment", "accountHolder": "Anu Saagim", "educationType": "MSc", "loanPurposeType": "Bills", "mobilePhoneType": "PrePaid", "residentialType": "Own", "incomeSourceType": "Employed", "maritalStatusType": "Single"}',
          'created_at' => '2018-04-09 11:16:09',
          'application_hash' => '3a4a3902bcc263d367807fa559d5ac46d7befc18e10e62cc'
        ],
        [
          'loan_amount' => 2100,
          'loan_term' => 18,
          'first_name' => 'Adam',
          'last_name' => 'Barański',
          'email' => 'brak@asakredyt.pl',
          'phone' => '+48739050381',
          'document_nr' => 'CCY014054',
          'data' => '{"pin": "71121513234", "zip": "44-285", "city": "Kornowac", "trade": "unknown", "street": "Wolności", "houseNr": "60", "position": "Cleaning man", "accountNr": "32132345678901234569415123", "yearSince": 2013, "monthSince": 1, "accountType": "Personal", "apartmentNr": " ", "netPerMonth": 4400, "currentStudy": "", "employerName": "European Parliament", "payoutMethod": "Account", "propertyType": "Apartment", "accountHolder": "Anu Saagim", "educationType": "MSc", "loanPurposeType": "Bills", "mobilePhoneType": "PrePaid", "residentialType": "Own", "incomeSourceType": "Employed", "maritalStatusType": "Single"}',
          'created_at' => '2018-04-10 13:40:20',
          'application_hash' => '35ff180e7fb4f9121072192116201c5c96cd2498140c0855'
        ]
      ];

      $this->table('application')
        ->insert($appData)
        ->save();

      $partnerData = [
        [
          'name' => 'Ferratum',
          'identifier' => 'FERR',
          'status' => 0,
          'commission' => 1
        ]
      ];

      $this->table('partner')
        ->insert($partnerData)
        ->save();

      $offerData = [
        [
          'application_id' => 3,
          'partner_id' => 1,
          'loan_amount' => 10000,
          'loan_term' => 36,
          'interest' => null,
          'monthly_fee' => null,
          'rejected_date' => '2018-04-09 10:46:57',
          'accepted_date' => null,
          'paid_out_date' => null,
          'remote_id' => '31896775',
          'data' => '{"bic": "", "ref": "31896775", "bank": "BANK ZACHODNI WBK S.A.", "iban": "64 2190 0002 3000 0044 3042 0103", "saldo": "0.00", "token": "65368e7d-e688-4491-bd2c-1f1fe0f6de69", "active": false, "status": "Rejected", "account": "60109000048181000031896775", "appType": 15, "autoGiro": null, "bankGiro": "80132000191500150020000001", "esignUrl": null, "recipient": "Aasa Polska S.A.", "pepConsent": "Y", "resignDate": "0000-00-00", "contractUrl": null, "lastPayment": null, "loanDetails": null, "paidOutDate": "0000-00-00", "partnerType": "own_web", "totalUnpaid": null, "firstDueDate": "2018-05-09", "payoutMethod": "Account", "rejectReason": "DoubleApplication", "rejectedDate": {"date": "2018-04-10 07:04:54.951919", "timezone": "UTC", "timezone_type": 3}, "toBankAmount": 10000, "contractStatus": "Not signed", "insuranceStatus": "NotSet", "prePaymentSaldo": "0.00", "pepConsentFamily": null, "acceptancePageUrl": null, "aasaAsBeneficiaryConsent": null, "insuranceMarketingConsent": null}',
          'created_at' => '2018-04-09 10:46:57',
          'updated_at' => '2018-04-10 07:04:54'
        ],
        [
          'application_id' => 4,
          'partner_id' => 1,
          'loan_amount' => null,
          'loan_term' => null,
          'interest' => null,
          'monthly_fee' => null,
          'rejected_date' => '2018-04-09 10:51:28',
          'accepted_date' => null,
          'paid_out_date' => null,
          'remote_id' => '31896788',
          'data' => '{"token": "d1f8c375-6c61-4805-89fa-3c1184be1ddc", "status": "Rejected", "esignUrl": null, "contractUrl": null, "loanDetails": null, "rejectReason": "ScoreCard", "acceptancePageUrl": null}',
          'created_at' => '2018-04-09 10:51:28',
          'updated_at' => null
        ],
        [
          'application_id' => 5,
          'partner_id' => 1,
          'loan_amount' => null,
          'loan_term' => null,
          'interest' => null,
          'monthly_fee' => null,
          'rejected_date' => null,
          'accepted_date' => null,
          'paid_out_date' => null,
          'remote_id' => '31896791',
          'data' => '{"token": "8e226e71-1b25-45e7-9e2f-606ad2a86fc0", "status": "InProcess", "esignUrl": null, "contractUrl": null, "loanDetails": null, "rejectReason": null, "acceptancePageUrl": "https://www-test.aasapolska.pl/netbank/login?hash=cijSQ72Y4Ili0bfSupPColpoeW5GcEFQTXlQYkZveS9VY1JlUm9LWjc3V1NrUy9VWHh2VzMrWGRQS0k2U1dHcjArUXpwcW1sSUx1TFhXdnE4QUJPVDQxMzlwUG9Cb3ROYTUzTisrRT0%3D"}',
          'created_at' => '2018-04-09 14:23:59',
          'updated_at' => null
        ],
        [
          'application_id' => 6,
          'partner_id' => 1,
          'loan_amount' => null,
          'loan_term' => null,
          'interest' => null,
          'monthly_fee' => null,
          'rejected_date' => '2018-04-09 10:56:12',
          'accepted_date' => null,
          'paid_out_date' => null,
          'remote_id' => '31896801',
          'data' => '{"token": "433f8888-617e-4618-afcb-1d550683584f", "status": "Rejected", "esignUrl": null, "contractUrl": null, "loanDetails": null, "rejectReason": "DoubleApplication", "acceptancePageUrl": null}',
          'created_at' => '2018-04-09 10:56:12',
          'updated_at' => null
        ],
        [
          'application_id' => 7,
          'partner_id' => 2,
          'loan_amount' => null,
          'loan_term' => null,
          'interest' => null,
          'monthly_fee' => null,
          'rejected_date' => '2018-04-09 10:59:34',
          'accepted_date' => null,
          'paid_out_date' => null,
          'remote_id' => '31896814',
          'data' => '{"token": "ea317376-be65-47cc-8a96-ec1b1bee536d", "status": "Rejected", "esignUrl": null, "contractUrl": null, "loanDetails": null, "rejectReason": "ScoreCard", "acceptancePageUrl": null}',
          'created_at' => '2018-04-09 10:59:34',
          'updated_at' => null
        ],
        [
          'application_id' => 8,
          'partner_id' => 1,
          'loan_amount' => null,
          'loan_term' => null,
          'interest' => null,
          'monthly_fee' => null,
          'rejected_date' => '2018-04-09 14:00:23.0',
          'accepted_date' => null,
          'paid_out_date' => null,
          'remote_id' => '31896827',
          'data' => '{"token": "484303f3-2f04-4668-a6ef-812796b52131", "status": "Rejected", "esignUrl": null, "contractUrl": null, "loanDetails": null, "rejectReason": "ScoreCard", "acceptancePageUrl": null}',
          'created_at' => '2018-04-09 11:00:23',
          'updated_at' => null
        ],
        [
          'application_id' => 9,
          'partner_id' => 1,
          'loan_amount' => null,
          'loan_term' => null,
          'interest' => null,
          'monthly_fee' => null,
          'rejected_date' => null,
          'accepted_date' => null,
          'paid_out_date' => null,
          'remote_id' => '31896830',
          'data' => '{"token": "c2e83540-bfc4-4c65-8514-241b698541a0", "status": "InProcess", "esignUrl": null, "contractUrl": null, "loanDetails": null, "rejectReason": null, "acceptancePageUrl": "https://www-test.aasapolska.pl/netbank/login?hash=7SjVfo8mzbL0nToxk2CfmDY4bTN3aUMwNzJ6WkVncm9QTmJ6dnJ0bVI3dDdQaEI5djVDa1dLdGt6T2ZIQy83RGo5ckpaeGQvT21SZFpWZVBZSmx1T0JGWDdMR3hVSE16ajl6WWErZz0%3D"}',
          'created_at' => '2018-04-09 14:23:59',
          'updated_at' => null
        ],
        [
          'application_id' => 10,
          'partner_id' => 1,
          'loan_amount' => null,
          'loan_term' => null,
          'interest' => null,
          'monthly_fee' => null,
          'rejected_date' => '2018-04-10 13:40:27',
          'accepted_date' => null,
          'paid_out_date' => null,
          'remote_id' => '31896995',
          'data' => '{"token": "0503f454-81d7-4df1-bcdc-4ff6f35c9b14", "status": "Rejected", "esignUrl": null, "contractUrl": null, "loanDetails": null, "rejectReason": "DoubleApplication", "acceptancePageUrl": null}',
          'created_at' => '2018-04-10 13:40:27',
          'updated_at' => null
        ]
      ];

      $this->table('offer')
        ->insert($offerData)
        ->save();
    }
}
