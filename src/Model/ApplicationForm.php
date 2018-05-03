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
}