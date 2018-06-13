<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 03.04.18
 * Time: 16:13
 */

namespace App\Model;

class ApplicationForm
{
  const ATTR_INCOME_SOURCE = 'incomeSourceType';
  const ATTR_NET_PER_MONTH = 'netPerMonth';
  const ATTR_EMPLOYER_NAME = 'employerName'; //not required
  const ATTR_POSITION = 'position'; //not required
  const ATTR_EMPLOYER_FIELD = 'trade'; //not required
  const ATTR_YEAR_SINCE = 'yearSince'; //not required
  const ATTR_MONTH_SINCE = 'monthSince'; //not required
  const ATTR_CURRENT_STUDY = 'currentStudy'; //not required
  const ATTR_LOAN_PURPOSE = 'loanPurposeType';
  const ATTR_PIN = 'pin';
  const ATTR_STREET = 'street';
  const ATTR_ZIP = 'zip';
  const ATTR_HOUSE_NR = 'houseNr';
  const ATTR_APARTMENT_NR = 'apartmentNr';
  const ATTR_CITY = 'city';
  const ATTR_ACCOUNT_NR = 'accountNr';
  const ATTR_ACCOUNT_TYPE = 'accountType';
  const ATTR_ACCOUNT_HOLDER = 'accountHolder';
  const ATTR_DOCUMENT_NR = 'documentNr';
  const ATTR_PHONE_TYPE = 'mobilePhoneType'; //not required
  const ATTR_PAYOUT_METHOD = 'payoutMethod'; //not required
  const ATTR_EDUCATION = 'educationType';
  const ATTR_MARITAL_STATUS = 'maritalStatusType';
  const ATTR_RESIDENTIAL_TYPE = 'residentialType';
  const ATTR_PROPERTY_TYPE = 'propertyType';
  const ATTR_LOAN_AMOUNT = 'loanAmount';
  const ATTR_LOAN_TERM = 'loanTerm';
  const ATTR_FIRST_NAME = 'firstName';
  const ATTR_LAST_NAME = 'lastName';
  const ATTR_EMAIL = 'email';
  const ATTR_PHONE = 'phone';
  const ATTR_GDPR_1 = 'gdpr1';
  const ATTR_GDPR_2 = 'gdpr2';
  const ATTR_EMAIL_CONSENT = 'emailConsent';
  const ATTR_PHONE_CONSENT = 'phoneConsent';

  const ENUM_INCOME_EMPLOYED = 'employed';
  const ENUM_INCOME_STUDENT = 'student';
  const ENUM_INCOME_PENSION = 'pension';
  const ENUM_INCOME_DISABILITY = 'disabilityPension';
  const ENUM_INCOME_UNEMPLOYED = 'unemployed';
  const ENUM_INCOME_ALIMONY = 'alimony';
  const ENUM_INCOME_SELF_EMPLOYED = 'selfEmployed';
  const ENUM_INCOME_FARMER = 'farmer';
  const ENUM_INCOME_OTHER = 'other';

  const ENUM_EDUCATION_BASIC = 'basic';
  const ENUM_EDUCATION_SECONDARY = 'secondary';
  const ENUM_EDUCATION_VOCATIONAL = 'vocational';
  const ENUM_EDUCATION_BA = 'bachelors';
  const ENUM_EDUCATION_MA = 'mastersOfArt';
  const ENUM_EDUCATION_MBA = 'mastersOfBusiness';
  const ENUM_EDUCATION_MAs = 'mastersOfScience';
  const ENUM_EDUCATION_MD = 'doctors';
  const ENUM_EDUCATION_OTHER = 'other';

  const ENUM_PAYOUT_ACCOUNT = 'account';
  const ENUM_PAYOUT_GIRO = 'giro';
  const ENUM_PAYOUT_BLUECASH = 'bluecash';

  const ENUM_ACCOUNT_PERSONAL = 'personal';
  const ENUM_ACCOUNT_JOINT = 'joint';
  const ENUM_ACCOUNT_COMPANY = 'company';

  const ENUM_MARITAL_SINGLE = 'single';
  const ENUM_MARITAL_MARRIED = 'married';
  const ENUM_MARITAL_MARRIED_DIVORCING = 'marriedDivorcing';
  const ENUM_MARITAL_DIVORCED = 'divorced';
  const ENUM_MARITAL_SEPARATED = 'separated';
  const ENUM_MARITAL_WIDOW = 'widow';
  const ENUM_MARITAL_OTHER = 'other';

  const ENUM_RESIDENCY_OWN = 'own';
  const ENUM_RESIDENCY_RENT = 'rented';
  const ENUM_RESIDENCY_FAMILY = 'family';
  const ENUM_RESIDENCY_SOCIAL = 'social';
  const ENUM_RESIDENCY_OTHER = 'other';

  const ENUM_PROPERTY_FLAT = 'flat';
  const ENUM_PROPERTY_HOUSE = 'house';
  const ENUM_PROPERTY_DUPLEX = 'duplex';
  const ENUM_PROPERTY_OTHER = 'other';

  const ENUM_MONTH_JANUARY = 1;
  const ENUM_MONTH_FEBRUARY = 2;
  const ENUM_MONTH_MARCH = 3;
  const ENUM_MONTH_APRIL = 4;
  const ENUM_MONTH_MAY = 5;
  const ENUM_MONTH_JUNE = 6;
  const ENUM_MONTH_JULY = 7;
  const ENUM_MONTH_AUGUST = 8;
  const ENUM_MONTH_SEPTEMBER = 9;
  const ENUM_MONTH_OCTOBER = 10;
  const ENUM_MONTH_NOVEMBER = 11;
  const ENUM_MONTH_DECEMBER = 12;

  const ENUM_PURPOSE_BILLS = 'bills';
  const ENUM_PURPOSE_VACATION = 'vacation';
  const ENUM_PURPOSE_MORTGAGE = 'rentOrMortgage';
  const ENUM_PURPOSE_CAR = 'car';
  const ENUM_PURPOSE_ENTERTAINMENT = 'entertainment';
  const ENUM_PURPOSE_GROCERIES = 'groceries';
  const ENUM_PURPOSE_RENOVATION = 'renovation';
  const ENUM_PURPOSE_ELECTRONICS = 'electronics';
  const ENUM_PURPOSE_FURNITURE = 'furniture';
  const ENUM_PURPOSE_SCHOOL = 'school';
  const ENUM_PURPOSE_TAX = 'taxPayment';
  const ENUM_PURPOSE_INVESTMENT = 'investment';
  const ENUM_PURPOSE_INVOICE = 'invoicePayment';
  const ENUM_PURPOSE_RENT = 'rent';
  const ENUM_PURPOSE_OTHER = 'other';

  const SECTION_GENERAL = 'general';
  const SECTION_PERSONAL = 'personal';
  const SECTION_ADDITIONAL = 'additional';
  const SECTION_INCOME = 'income';
  const SECTION_HOUSING = 'housing';
  const SECTION_ACCOUNT = 'account';

  public static function getFieldParameters()
  {
    return [
      self::ATTR_INCOME_SOURCE => [
        'section' => self::SECTION_INCOME,
        'label' => _('Income type'),
        'order' => 1
      ],
      self::ATTR_NET_PER_MONTH => [
        'section' => self::SECTION_INCOME,
        'label' => _('Your monthly net income'),
        'order' => 2
      ],
      self::ATTR_YEAR_SINCE => [
        'section' => self::SECTION_INCOME,
        'label' => _('Year started at the organization'),
        'order' => 4
      ],
      self::ATTR_MONTH_SINCE => [
        'section' => self::SECTION_INCOME,
        'label' => _('Month started at the organization'),
        'order' => 3
      ],
      self::ATTR_LOAN_PURPOSE => [
        'section' => self::SECTION_ADDITIONAL,
        'label' => _('Purpose of loan'),
        'order' => 1
      ],
      self::ATTR_PIN => [
        'section' => self::SECTION_PERSONAL,
        'label' => _('Personal identification number'),
        'order' => 3
      ],
      self::ATTR_STREET => [
        'section' => self::SECTION_HOUSING,
        'label' => _('Street'),
        'order' => 3
      ],
      self::ATTR_ZIP => [
        'section' => self::SECTION_HOUSING,
        'label' => _('ZIP'),
        'order' => 7
      ],
      self::ATTR_HOUSE_NR => [
        'section' => self::SECTION_HOUSING,
        'label' => _('House number'),
        'order' => 4
      ],
      self::ATTR_APARTMENT_NR => [
        'section' => self::SECTION_HOUSING,
        'label' => _('Apartment number'),
        'order' => 5
      ],
      self::ATTR_CITY => [
        'section' => self::SECTION_HOUSING,
        'label' => _('City'),
        'order' => 6
      ],
      self::ATTR_ACCOUNT_NR => [
        'section' => self::SECTION_ACCOUNT,
        'label' => _('Account number'),
      ],
      self::ATTR_ACCOUNT_TYPE => [
        'section' => self::SECTION_ACCOUNT,
        'label' => _('Account type'),
      ],
      self::ATTR_ACCOUNT_HOLDER => [
        'section' => self::SECTION_ACCOUNT,
        'label' => _('Account holder\'s name')
      ],
      self::ATTR_DOCUMENT_NR => [
        'section' => self::SECTION_PERSONAL,
        'label' => _('ID Card number'),
        'order' => 4
      ],
      self::ATTR_PAYOUT_METHOD => [
        'section' => self::SECTION_ADDITIONAL,
        'label' => _('Payout method'),
        'order' => 1
      ],
      self::ATTR_EDUCATION => [
        'section' => self::SECTION_PERSONAL,
        'label' => _('Education level'),
        'order' => 7
      ],
      self::ATTR_MARITAL_STATUS => [
        'section' => self::SECTION_PERSONAL,
        'label' => _('Marital status'),
        'order' => 8
      ],
      self::ATTR_RESIDENTIAL_TYPE => [
        'section' => self::SECTION_HOUSING,
        'label' => _('Type of residency'),
        'order' => 1
      ],
      self::ATTR_PROPERTY_TYPE => [
        'section' => self::SECTION_HOUSING,
        'label' => _('Type of property'),
        'order' => 2
      ],
      self::ATTR_LOAN_AMOUNT => [
        'section' => self::SECTION_GENERAL,
        'label' => _('Loan amount')
      ],
      self::ATTR_LOAN_TERM => [
        'section' => self::SECTION_GENERAL,
        'label' => _('Loan term')
      ],
      self::ATTR_FIRST_NAME => [
        'section' => self::SECTION_PERSONAL,
        'label' => _('First name'),
        'order' => 1
      ],
      self::ATTR_LAST_NAME => [
        'section' => self::SECTION_PERSONAL,
        'label' => _('Last name'),
        'order' => 2
      ],
      self::ATTR_EMAIL => [
        'section' => self::SECTION_PERSONAL,
        'label' => _('Email'),
        'order' => 5
      ],
      self::ATTR_PHONE => [
        'section' => self::SECTION_PERSONAL,
        'label' => _('Phone'),
        'order' => 6
      ]
    ];
  }

  public static function getEnumFields()
  {
    return [
      self::ATTR_INCOME_SOURCE => [
        self::ENUM_INCOME_EMPLOYED => _('Employed'),
        self::ENUM_INCOME_STUDENT => _('Student'),
        self::ENUM_INCOME_PENSION => _('Normal Pension'),
        self::ENUM_INCOME_DISABILITY => _('Disability Pension'),
        self::ENUM_INCOME_UNEMPLOYED => _('Unemployed'),
        self::ENUM_INCOME_ALIMONY => _('Alimony'),
        self::ENUM_INCOME_SELF_EMPLOYED => _('Self Employed'),
        self::ENUM_INCOME_FARMER => _('Farmer')
      ],
      self::ATTR_EDUCATION => [
        self::ENUM_EDUCATION_BASIC => _('Basic'),
        self::ENUM_EDUCATION_SECONDARY => _('Secondary'),
        self::ENUM_EDUCATION_VOCATIONAL => _('Vocational'),
        self::ENUM_EDUCATION_BA => _('Bachelor of Science'),
        self::ENUM_EDUCATION_MA => _('Master of Arts'),
        self::ENUM_EDUCATION_MBA => _('Master of Business Administration'),
        self::ENUM_EDUCATION_MAs => _('Master of Advanced Studies'),
        self::ENUM_EDUCATION_MD => _('Medical Doctorate'),
        self::ENUM_EDUCATION_OTHER => _('Other')
      ],
      self::ATTR_MARITAL_STATUS => [
        self::ENUM_MARITAL_SINGLE => _('Single'),
        self::ENUM_MARITAL_MARRIED => _('Married'),
        self::ENUM_MARITAL_MARRIED_DIVORCING => _('Married, but divorcing'),
        self::ENUM_MARITAL_DIVORCED => _('Divorced'),
        self::ENUM_MARITAL_SEPARATED => _('Separated'),
        self::ENUM_MARITAL_WIDOW => _('Widow'),
        self::ENUM_MARITAL_OTHER => _('Other')
      ],
      self::ATTR_ACCOUNT_TYPE => [
        self::ENUM_ACCOUNT_PERSONAL => _('Personal'),
        self::ENUM_ACCOUNT_JOINT => _('Joint')
      ],
      self::ATTR_RESIDENTIAL_TYPE => [
        self::ENUM_RESIDENCY_OWN => _('I\'m an owner'),
        self::ENUM_RESIDENCY_RENT => _('I\'m renting'),
        self::ENUM_RESIDENCY_FAMILY => _('I\'m living with family'),
        self::ENUM_RESIDENCY_SOCIAL => _('Social housing'),
        self::ENUM_RESIDENCY_OTHER => _('Other')
      ],
      self::ATTR_PROPERTY_TYPE => [
        self::ENUM_PROPERTY_FLAT => _('Apartment'),
        self::ENUM_PROPERTY_HOUSE => _('House'),
        self::ENUM_PROPERTY_DUPLEX => _('Duplex'),
        self::ENUM_PROPERTY_OTHER => _('Other')
      ],
      self::ATTR_MONTH_SINCE => [
        self::ENUM_MONTH_JANUARY => strftime('%B', mktime(0,0,0,1)),
        self::ENUM_MONTH_FEBRUARY => strftime('%B', mktime(0,0,0,2)),
        self::ENUM_MONTH_MARCH => strftime('%B', mktime(0,0,0, 3)),
        self::ENUM_MONTH_APRIL => strftime('%B', mktime(0,0,0,4)),
        self::ENUM_MONTH_MAY => strftime('%B', mktime(0,0,0,5)),
        self::ENUM_MONTH_JUNE => strftime('%B', mktime(0,0,0,6)),
        self::ENUM_MONTH_JULY => strftime('%B', mktime(0,0,0,7)),
        self::ENUM_MONTH_AUGUST => strftime('%B', mktime(0,0,0,8)),
        self::ENUM_MONTH_SEPTEMBER => strftime('%B', mktime(0,0,0,9)),
        self::ENUM_MONTH_OCTOBER => strftime('%B', mktime(0,0,0,10)),
        self::ENUM_MONTH_NOVEMBER => strftime('%B', mktime(0,0,0,11)),
        self::ENUM_MONTH_DECEMBER => strftime('%B', mktime(0,0,0,12))
      ],
      self::ATTR_PAYOUT_METHOD => [
        self::ENUM_PAYOUT_ACCOUNT => _('Account'),
        self::ENUM_PAYOUT_GIRO => _('Giro')
      ],
      self::ATTR_LOAN_PURPOSE => [
        "" => "",
        self::ENUM_PURPOSE_BILLS => _('Bills'),
        self::ENUM_PURPOSE_VACATION => _('Vacation'),
        self::ENUM_PURPOSE_MORTGAGE => _('Rent or Mortgage'),
        self::ENUM_PURPOSE_CAR => _('Car'),
        self::ENUM_PURPOSE_ENTERTAINMENT => _('Entertainment'),
        self::ENUM_PURPOSE_GROCERIES => _('Groceries'),
        self::ENUM_PURPOSE_RENOVATION => _('Renovation'),
        self::ENUM_PURPOSE_ELECTRONICS => _('Electronics'),
        self::ENUM_PURPOSE_FURNITURE => _('Furniture'),
        self::ENUM_PURPOSE_SCHOOL => _('School'),
        self::ENUM_PURPOSE_TAX => _('Tax Payment'),
        self::ENUM_PURPOSE_INVESTMENT => _('Investment'),
        self::ENUM_PURPOSE_INVOICE => _('Invoice Payment'),
        self::ENUM_PURPOSE_RENT => _('Rent'),
        self::ENUM_PURPOSE_OTHER => _('Other')
      ]
    ];
  }

  /**
   * @param string $field
   * @return mixed|null
   */
  public function getFieldLabel(string $field)
  {
    $labels = $this->labels();
    return $labels[$field] ?? null;
  }

  /**
   * @return array
   */
  public function labels()
  {
    return [
      self::ATTR_INCOME_SOURCE => _('Income type'),
      self::ATTR_NET_PER_MONTH => _('Your monthly net income'),
      self::ATTR_EMPLOYER_NAME => _('Employer name'),
      self::ATTR_POSITION => _('Your position'),
      self::ATTR_EMPLOYER_FIELD => _('Organization\'s field of activity'),
      self::ATTR_YEAR_SINCE => _('Year started at the organization'),
      self::ATTR_MONTH_SINCE => _('Month started at the organization'),
      self::ATTR_CURRENT_STUDY => _('Education provider'),
      self::ATTR_LOAN_PURPOSE => _('Purpose of loan'),
      self::ATTR_PIN => _('Personal identification number'),
      self::ATTR_STREET => _('Street'),
      self::ATTR_ZIP => _('ZIP'),
      self::ATTR_HOUSE_NR => _('House number'),
      self::ATTR_APARTMENT_NR => _('Apartment number'),
      self::ATTR_CITY => _('City'),
      self::ATTR_ACCOUNT_NR => _('Account number'),
      self::ATTR_ACCOUNT_TYPE => _('Account type'),
      self::ATTR_ACCOUNT_HOLDER => _('Account holder\'s name'),
      self::ATTR_DOCUMENT_NR => _('ID Card number'),
      self::ATTR_PHONE_TYPE => _('Phone type'),
      self::ATTR_PAYOUT_METHOD => _('Payout method'),
      self::ATTR_EDUCATION => _('Education level'),
      self::ATTR_MARITAL_STATUS => _('Marital status'),
      self::ATTR_RESIDENTIAL_TYPE => _('Type of residency'),
      self::ATTR_PROPERTY_TYPE => _('Type of property'),
      self::ATTR_LOAN_AMOUNT => _('Loan amount'),
      self::ATTR_LOAN_TERM => _('Loan term'),
      self::ATTR_FIRST_NAME => _('First name'),
      self::ATTR_LAST_NAME => _('Last name'),
      self::ATTR_EMAIL => _('Email'),
      self::ATTR_PHONE => _('Phone'),
      self::ATTR_GDPR_1 => _('I agree to the processing by Q Credit Sp. z o.o my personal data for marketing purposes, in terms of my address, address for correspondence and e-mail address. I have been informed of the right to inspect the data concerning me, the right to request a change and deletion of such data, as well as the right to object to the processing of my personal data.'),
      self::ATTR_GDPR_2 => _('I agree to the processing of my personal data byQ Credit Sp. z o.o and by entities belonging to the capital group, which includes Q Credit Sp. z o.o in order to marketing products of these entities and third parties cooperating with Q Credit Sp. z o.o'),
      self::ATTR_EMAIL_CONSENT => _('I agree to receive from Q Credit Sp. z o.o commercial information sent via email.'),
      self::ATTR_PHONE_CONSENT => _('I agree to receive from Q Credit Sp. z o.o commercial information provided via the telephone.')
    ];
  }

  /**
   * @return array
   */
  public function getAjvErrors()
  {
    return [
      '$ref' => _('Invalid schema reference given'),
      'additionalItems' => _('Field should not have more than {items} items'),
      'additionalProperties' => _('Must not contain additional properties'),
      'anyOf' => _('Must match some schema in "anyOf"'),
      'const' => _('Must be equal to constant'),
      'contains' => _('Must contain a valid item'),
      'custom' => _('Invalid format provided'),
      'dependencies' => _('Must have property "{property1}" when property "{property2}" is present'),
      'enum' => _('Value must match the given list'),
      'exclusiveMaximum' => _('Must be less than {number}'),
      'exclusiveMinimum' => _('Must me more than {number}'),
      'false schema' => _('Boolean schema is false'),
      'format' => _('Invalid format'),
      'formatExclusiveMaximum' => _('Must be boolean'),
      'formatExclusiveMinimum' => _('Must be boolean'),
      'formatMaximum' => _('Must be less than {maximum}'),
      'formatMinimum' => _('Must be at least {minimum}'),
      'if' => _('Must match {schemaName} schema'),
      'maximum' => _('Must be less than or equal to {maximum}'),
      'maxItems' => _('Must not have more than {number} items'),
      'maxLength' => _('Must not exceed {number} characters'),
      'maxProperties' => _('Must not have more than {number} properties'),
      'minimum' => _('Must have a minimum value of {minimum}'),
      'minItems' => _('Must not have less than {number] items'),
      'minLength' => _('Must be at least {number} characters'),
      'minProperties' => _('Must not have less than {number} properties'),
      'multipleOf' => _('Must be a multiple of {number}'),
      'not' => _('Must not be valid according to schema in "not"'),
      'oneOf' => _('Must match exactly one schema in "oneOf"'),
      'pattern' => _('Invalid format'),
      'patternRequired' => _('Must have a property matching a required pattern'),
      'propertyNames' => _('Property name "{name}" is invalid'),
      'required' => _('Must have required property {property}'),
      'switch' => _('Must pass "switch" keyword validation, case {case} fails'),
      'type' => _('Must be of type {type}'),
      'uniqueItems' => _('Must not have duplicate items')
    ];
  }
}