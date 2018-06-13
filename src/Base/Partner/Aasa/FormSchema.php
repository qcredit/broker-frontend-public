<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 12.06.18
 * Time: 15:40
 */

namespace App\Base\Partner\Aasa;

use App\Model\ApplicationForm;
use Broker\Domain\Interfaces\Partner\SchemaInterface;

class FormSchema implements SchemaInterface
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
        ApplicationForm::ATTR_LOAN_AMOUNT,
        ApplicationForm::ATTR_LOAN_TERM,
        ApplicationForm::ATTR_FIRST_NAME,
        ApplicationForm::ATTR_LAST_NAME,
        ApplicationForm::ATTR_PAYOUT_METHOD,
        ApplicationForm::ATTR_DOCUMENT_NR,
        ApplicationForm::ATTR_PHONE,
        ApplicationForm::ATTR_EDUCATION,
        ApplicationForm::ATTR_PIN,
        ApplicationForm::ATTR_EMAIL,
        ApplicationForm::ATTR_MARITAL_STATUS,
        ApplicationForm::ATTR_ACCOUNT_NR,
        ApplicationForm::ATTR_ACCOUNT_TYPE,
        ApplicationForm::ATTR_ACCOUNT_HOLDER,
        ApplicationForm::ATTR_RESIDENTIAL_TYPE,
        ApplicationForm::ATTR_PROPERTY_TYPE
      ],
      'properties' => [
        ApplicationForm::ATTR_INCOME_SOURCE => [
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
        ApplicationForm::ATTR_NET_PER_MONTH => [
          'type' => 'number'
        ],
        ApplicationForm::ATTR_YEAR_SINCE => [
          'type' => 'number',
          'minimum' => 1900,
          'maximum' => 2030
        ],
        ApplicationForm::ATTR_MONTH_SINCE => [
          'type' => 'integer',
          'minimum' => 1,
          'maximum' => 12,
          'enum' => [1,2,3,4,5,6,7,8,9,10,11,12]
        ],
        ApplicationForm::ATTR_LOAN_PURPOSE => [
          'type' => 'string',
          'enum' => [
            "",
            "Bills",
            "Vacation",
            "RentOrMortgage",
            "Car",
            "Entertainment",
            "Groceries",
            "Renovation",
            "Electronics",
            "Furniture",
            "School",
            "TaxPayment",
            "Investment",
            "InvoicePayment",
            "Rent",
            "Other"
          ]
        ],
        ApplicationForm::ATTR_PIN => [
          'type' => 'string',
          'maxLength' => 11,
          'pattern' => '[0-9]{4}[0-3]{1}[0-9}{1}[0-9]{5}'
        ],
        ApplicationForm::ATTR_STREET => [
          'type' => 'string',
          'maxLength' => 100,
          'minLength' => 2
        ],
        ApplicationForm::ATTR_ZIP => [
          'type' => 'string',
          'minLength' => 6,
          'pattern' => '^[0-9]{2}-[0-9]{3}$'
        ],
        ApplicationForm::ATTR_HOUSE_NR => [
          'type' => 'string',
          'maxLength' => 10,
          'minLength' => 1
        ],
        ApplicationForm::ATTR_APARTMENT_NR => [
          'type' => 'string',
          'maxLength' => 10
        ],
        ApplicationForm::ATTR_CITY => [
          'type' => 'string',
          'maxLength' => 50,
          'minLength' => 2
        ],
        ApplicationForm::ATTR_ACCOUNT_NR => [
          'type' => 'string',
          'minLength' => 26,
          'pattern' => '^[0-9]{26}$'
        ],
        ApplicationForm::ATTR_ACCOUNT_TYPE => [
          'type' => 'string',
          'enum' => [
            'Personal',
            'Joint',
            'Company'
          ]
        ],
        ApplicationForm::ATTR_ACCOUNT_HOLDER => [
          'type' => 'string',
          'minLength' => 1
        ],
        ApplicationForm::ATTR_DOCUMENT_NR => [
          'type' => 'string',
          'minLength' => 9,
          'documentNr' => 'Poland'
        ],
        ApplicationForm::ATTR_EDUCATION => [
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
        ApplicationForm::ATTR_MARITAL_STATUS => [
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
        ApplicationForm::ATTR_RESIDENTIAL_TYPE => [
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
        ApplicationForm::ATTR_PROPERTY_TYPE => [
          'type' => 'string',
          'enum' => [
            'Apartment',
            'House',
            'TerracedHouse',
            'Duplex',
            'Other'
          ]
        ],
        ApplicationForm::ATTR_LOAN_AMOUNT => [
          'type' => 'number',
          'minimum' => 10,
        ],
        ApplicationForm::ATTR_LOAN_TERM => [
          'type' => 'number',
          'minimum' => 1
        ],
        ApplicationForm::ATTR_FIRST_NAME => [
          'type' => 'string',
          'maxLength' => 50,
          'minLength' => 1
        ],
        ApplicationForm::ATTR_LAST_NAME => [
          'type' => 'string',
          'maxLength' => 50,
          'minLength' => 1
        ],
        ApplicationForm::ATTR_EMAIL => [
          'type' => 'string',
          'minLength' => 4,
          'pattern' => '\S+@\S+\.\S+'
        ],
        ApplicationForm::ATTR_PHONE => [
          'type' => 'string',
          'minLength' => 9,
          'pattern' => '^@?((\+48)?(\d{9})|(\+372)\d{7,8})\b'
        ],
        ApplicationForm::ATTR_PAYOUT_METHOD => [
          'type' => 'string',
          'enum' => [
            "Giro",
            "Account"
          ],
          'order' => 1
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
   * @return string
   */
  public function getJsonSchema(): string
  {
    return json_encode($this->schema);
  }
}