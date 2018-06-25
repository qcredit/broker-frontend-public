<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 21.06.18
 * Time: 15:19
 */

namespace App\Base\Partner\Aasa;

use Broker\Domain\Interfaces\Partner\SchemaInterface;

class RequestSchema implements SchemaInterface
{
  /**
   * @var array
   */
  protected $schema;

  public function __construct()
  {
    $this->schema = [
      'type' => 'object',
      'required' => [
        'loanAmount',
        'loanPeriodInMonths',
        'firstName',
        'lastName',
        'payoutMethod',
        'contactAddress',
        'mainAddress',
        'idCardNumber',
        'mobilePhoneNumber',
        'educationType',
        'housing',
        'income',
        'pin',
        'emailAddress',
        'maritalStatusType'
      ],
      'properties' => [
        'income' => [
          'type' => 'object',
          'required' => [
            'sourceType',
            'netPerMonth',
            'yearSince',
            'monthSince'
          ],
          'properties' => [
            'sourceType' => [
              '$ref' => '#/definitions/sourceTypes'
            ],
            'netPerMonth' => [
              'type' => 'number'
            ],
            'yearSince' => [
              'type' => 'number',
              'minimum' => 1900,
              'maximum' => 2030
            ],
            'monthSince' => [
              'type' => 'integer',
              'minimum' => 1,
              'maximum' => 12,
              'enum' => [
                1,2,3,4,5,6,7,8,9,10,11,12
              ]
            ]
          ]
        ],
        'pin' => [
          'type' => 'string',
          'maxLength' => 11,
          'pattern' => '[0-9]{4}[0-3]{1}[0-9}{1}[0-9]{5}'
        ],
        'contactAddress' => [
          '$ref' => '#/definitions/addressObject'
        ],
        'mainAddress' => [
          '$ref' => '#/definitions/addressObject'
        ],
        'account' => [
          '$ref' => '#/definitions/accountObject'
        ],
        'idCardNumber' => [
          'type' => 'string',
          'minLength' => 9,
          'documentNr' => 'Poland'
        ],
        'payoutMethod' => [
          'type' => 'string',
          'enum' => [
            'Giro',
            'BlueCash',
            'Account'
          ]
        ],
        'educationType' => [
          'type' => 'string',
          'enum' => [
            'MBA',
            'MSc',
            'BA',
            'Secondary',
            'Vocational',
            'Basic',
            'Other'
          ]
        ],
        'maritalStatusType' => [
          'type' => 'string',
          'enum' => [
            'Single',
            'Married',
            'MarriedDivorcing',
            'Divorced',
            'Separated',
            'Widow',
            'InformationRelationship',
            'Other'
          ]
        ],
        'housing' => [
          '$ref' => '#/definitions/housingObject'
        ],
        'loanAmount' => [
          'type' => 'number',
          'minimum' => 10
        ],
        'loanPeriodInMonths' => [
          'type' => 'number',
          'minimum' => 1
        ],
        'firstName' => [
          'type' => 'string',
          'maxLength' => 50,
          'minLength' => 1
        ],
        'lastName' => [
          'type' => 'string',
          'maxLength' => 50,
          'minLength' => 1
        ],
        'emailAddress' => [
          'type' => 'string',
          'minLength' => 4,
          'pattern' => '\\S+@\\S+\\.\\S+'
        ],
        'mobilePhoneNumber' => [
          'type' => 'string',
          'minLength' => 9,
          'pattern' => '^@?((\\+48)?(\\d{9})|(\\+372)\\d{7,8})\\b'
        ],
        'loanPurposeType' => [
          'type' => 'string',
          'enum' => [
            '',
            'Bills',
            'Vacation',
            'RentOrMortgage',
            'Car',
            'Entertainment',
            'Groceries',
            'Renovation',
            'Electronics',
            'Furniture',
            'School',
            'TaxPayment',
            'Investment',
            'InvoicePayment',
            'Rent',
            'Other'
          ]
        ]
      ],
      'definitions' => [
        'sourceTypes' => [
          'type' => 'string',
          'enum' => [
            'Employed',
            'Student',
            'NormalPension',
            'DisabilityPension',
            'Unemployed',
            'BenefitOrAlimony',
            'SelfEmployed',
            'Farmer',
            'Other'
          ]
        ],
        'addressObject' => [
          'required' => [
            'street',
            'houseNumber',
            'postalCode',
            'city'
          ],
          'properties' => [
            'street' => [
              'type' => 'string',
              'maxLength' => 100,
              'minLength' => 2
            ],
            'postalCode' => [
              'type' => 'string',
              'minLength' => 6,
              'pattern' => '^[0-9]{2}-[0-9]{3}$'
            ],
            'houseNumber' => [
              'type' => 'string',
              'maxLength' => 10,
              'minLength' => 1
            ],
            'apartmentNumber' => [
              'type' => 'string',
              'maxLength' => 10
            ],
            'city' => [
              'type' => 'string',
              'maxLength' => 50,
              'minLength' => 2
            ]
          ]
        ],
        'accountObject' => [
          'required' => [
            'accountNumber',
            'accountType',
            'accountHolder'
          ],
          'properties' => [
            'accountNumber' => [
              'type' => 'string',
              'minLength' => 26,
              'pattern' => '^[0-9]{26}$'
            ],
            'accountType' => [
              'type' => 'string',
              'enum' => [
                'Personal',
                'Joint',
                'Company'
              ]
            ],
            'accountHolder' => [
              'type' => 'string',
              'minLength' => 1
            ]
          ]
        ],
        'housingObject' => [
          'required' => [
            'residentialType',
            'propertyType'
          ],
          'properties' => [
            'residentialType' => [
              'type' => 'string',
              'enum' => [
                'Own',
                'Rented',
                'LivingWithFamily',
                'Other',
                'CouncilHousing',
                'HousingAssociation'
              ]
            ],
            'propertyType' => [
              'type' => 'string',
              'enum' => [
                'Apartment',
                'House',
                'TerracedHouse',
                'Duplex',
                'Other'
              ]
            ]
          ]
        ]
      ]
    ];
  }

  /**
   * @return array
   */
  public function getSchema()
  {
    return $this->schema;
  }

  /**
   * Specify data which should be serialized to JSON
   * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
   * @return mixed data which can be serialized by <b>json_encode</b>,
   * which is a value of any type other than a resource.
   * @since 5.4.0
   */
  public function jsonSerialize()
  {
    return $this->schema;
  }
}