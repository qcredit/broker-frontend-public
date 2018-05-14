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
      self::ATTR_DOCUMENT_NR => _('Document number'),
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
      self::ATTR_MARKETING_CONSENT => _('Yes, you can send me information about promotions, competitions, offers and such.')
    ];
  }
}