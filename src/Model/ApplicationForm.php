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
  const ATTR_MARKETING_CONSENT = 'marketingConsent';

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
      self::ATTR_INCOME_SOURCE => 'Income type',
      self::ATTR_NET_PER_MONTH => 'Your monthly net income',
      self::ATTR_EMPLOYER_NAME => 'Employer name',
      self::ATTR_POSITION => 'Your position',
      self::ATTR_EMPLOYER_FIELD => 'Organization\'s field of activity',
      self::ATTR_YEAR_SINCE => 'Year started at the organization',
      self::ATTR_MONTH_SINCE => 'Month started at the organization',
      self::ATTR_CURRENT_STUDY => 'Education provider',
      self::ATTR_LOAN_PURPOSE => 'Purpose of loan',
      self::ATTR_PIN => 'Personal identification number',
      self::ATTR_STREET => 'Street',
      self::ATTR_ZIP => 'ZIP',
      self::ATTR_HOUSE_NR => 'House number',
      self::ATTR_APARTMENT_NR => 'Apartment number',
      self::ATTR_CITY => 'City',
      self::ATTR_ACCOUNT_NR => 'Account number',
      self::ATTR_ACCOUNT_TYPE => 'Account type',
      self::ATTR_ACCOUNT_HOLDER => 'Account holder\'s name',
      self::ATTR_DOCUMENT_NR => 'Document number',
      self::ATTR_PHONE_TYPE => 'Phone type',
      self::ATTR_PAYOUT_METHOD => 'Payout method',
      self::ATTR_EDUCATION => 'Education level',
      self::ATTR_MARITAL_STATUS => 'Marital status',
      self::ATTR_RESIDENTIAL_TYPE => 'Type of residency',
      self::ATTR_PROPERTY_TYPE => 'Type of property',
      self::ATTR_LOAN_AMOUNT => 'Loan amount',
      self::ATTR_LOAN_TERM => 'Loan term',
      self::ATTR_FIRST_NAME => 'First name',
      self::ATTR_LAST_NAME => 'Last name',
      self::ATTR_EMAIL => 'Email',
      self::ATTR_PHONE => 'Phone',
      self::ATTR_MARKETING_CONSENT => 'Yes, you can send me information about promotions, competitions, offers and such.'
    ];
  }
}