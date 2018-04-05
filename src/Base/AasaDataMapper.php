<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 28.03.18
 * Time: 17:38
 */

namespace App\Base;

use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\PartnerResponse;
use Broker\Domain\Interfaces\PartnerDataMapperInterface;
use App\Model\ApplicationForm;

class AasaDataMapper implements PartnerDataMapperInterface
{
  const STATUS_IN_PROCESS = 'InProcess';
  const STATUS_ACTIVE = 'Active';
  const STATUS_PAID_BACK = 'PaidBack';
  const STATUS_IN_DEBT = 'InDebt';
  const STATUS_REJECTED = 'Rejected';

  /**
   * @return string
   */
  public function getRequestSchema(): string
  {
    return file_get_contents(dirname(__FILE__) . '/Config/aasa.schema.json');
  }

  /**
   * @return array
   */
  public function getRequestPayload()
  {
    return [
      'income' => [
        'sourceType' => ApplicationForm::ATTR_INCOME_SOURCE,
        'netPerMonth' => ApplicationForm::ATTR_NET_PER_MONTH,
        'employerName' => ApplicationForm::ATTR_EMPLOYER_NAME,
        'position' => ApplicationForm::ATTR_POSITION,
        'trade' => ApplicationForm::ATTR_EMPLOYER_FIELD,
        'yearSince' => ApplicationForm::ATTR_YEAR_SINCE,
        'monthSince' => ApplicationForm::ATTR_MONTH_SINCE,
        'currentStudy' => ApplicationForm::ATTR_CURRENT_STUDY
      ],
      'loanPurposeType' => ApplicationForm::ATTR_LOAN_PURPOSE,
      'pin' => ApplicationForm::ATTR_PIN,
      'contactAddress' => [
        'street' => ApplicationForm::ATTR_STREET,
        'postalCode' => ApplicationForm::ATTR_ZIP,
        'houseNumber' => ApplicationForm::ATTR_HOUSE_NR,
        'apartmentNumber' => ApplicationForm::ATTR_APARTMENT_NR,
        'city' => ApplicationForm::ATTR_CITY
      ],
      'mainAddress' => [
        'street' => ApplicationForm::ATTR_STREET,
        'postalCode' => ApplicationForm::ATTR_ZIP,
        'houseNumber' => ApplicationForm::ATTR_HOUSE_NR,
        'apartmentNumber' => ApplicationForm::ATTR_APARTMENT_NR,
        'city' => ApplicationForm::ATTR_CITY
      ],
      'account' => [
        'accountNumber' => ApplicationForm::ATTR_ACCOUNT_NR,
        'accountType' => ApplicationForm::ATTR_ACCOUNT_TYPE,
        'accountHolder' => ApplicationForm::ATTR_ACCOUNT_HOLDER
      ],
      'idCardNumber' => ApplicationForm::ATTR_DOCUMENT_NR,
      'mobilePhoneType' => ApplicationForm::ATTR_PHONE_TYPE,
      'payoutMethod' => ApplicationForm::ATTR_PAYOUT_METHOD,
      'educationType' => ApplicationForm::ATTR_EDUCATION,
      'maritalStatusType' => ApplicationForm::ATTR_MARITAL_STATUS,
      'housing' => [
        'residentialType' => ApplicationForm::ATTR_RESIDENTIAL_TYPE,
        'propertyType' => ApplicationForm::ATTR_PROPERTY_TYPE,
      ],
      'loanAmount' => ApplicationForm::ATTR_LOAN_AMOUNT,
      'loanPeriodInMonths' => ApplicationForm::ATTR_LOAN_TERM,
      'firstName' => ApplicationForm::ATTR_FIRST_NAME,
      'lastName' => ApplicationForm::ATTR_LAST_NAME,
      'emailAddress' => ApplicationForm::ATTR_EMAIL,
      'mobilePhoneNumber' => ApplicationForm::ATTR_PHONE,
/*      'eMarketing' => 'Y',
      'pMarketing' => 'Y',
      'tMarketing' => 'Y'*/
    ];
  }

  /**
   * @return array
   */
  public function getResponsePayload()
  {
    return [
      'id' => 'remoteId',
      'amount' => 'amount',
      'period' => 'term',
      'interest' => 'interest',
      'avg' => 'monthlyFee'
    ];
  }

  /**
   * @param $item
   * @param $key
   * @param $application
   */
  public function mapper(&$item, $key, $application)
  {
    if (is_array($item))
    {
      array_walk($item, [$this, 'mapper'], $application);
    }
    else {
      $item = $application->getAttribute($item);
    }
  }

  /**
   * @param Application $application
   * @return string
   */
  public function mapAppToRequest(Application $application)
  {
    $payload = $this->getRequestPayload();
    array_walk($payload, [$this, 'mapper'], $application);

    $payload['eMarketing'] = 'Y';
    $payload['pMarketing'] = 'Y';
    $payload['tMarketing'] = 'Y';

    return json_encode($payload);
  }

  /**
   * @param PartnerResponse $response
   * @return array
   */
  public function mapResponseToOffer(PartnerResponse $response)
  {
    $body = json_decode($response->getResponseBody(), true);

    $flatBody = $this->flattenArray($body);
    $map = $this->getResponsePayload();
    $data = [];
    foreach ($flatBody as $key => $value)
    {
      if (isset($map[$key]))
      {
        $data[$map[$key]] = $value;
        unset($flatBody[$key]);
      }
    }

    $data['data'] = $flatBody;
    if (isset($flatBody['status']) && $flatBody['status'] == self::STATUS_REJECTED)
    {
      $data['rejectedDate'] = new \DateTime();
    }

    return $data;
  }

  /**
   * @param PartnerResponse $response
   * @return array
   */
  public function getAdditionalErrors(PartnerResponse $response)
  {
    $errors = [];
    $body = json_decode($response->getResponseBody(), true);

    foreach ($body as $row)
    {
      if (is_array($row) && isset($row['field']))
      {
        $map = $this->flattenArray($this->getPayload());

        if (isset($map[$row['field']]))
        {
          $errors[$map[$row['field']]] = $row['message'];
        }
        else {
          $errors[$row['field']] = $row['message'];
        }
      }
    }

    return $errors;
  }

  /**
   * @param PartnerResponse $response
   * @return bool
   */
  public function responseHasErrors(PartnerResponse $response): bool
  {
    $body = json_decode($response->getResponseBody(), true);

    foreach ($body as $row)
    {
      if (is_array($row) && isset($row['field']))
      {
        return true;
      }
    }

    return false;
  }

  /**
   * @param $array
   * @return array
   */
  public static function flattenArray($array)
  {
    $output = array();
    array_walk_recursive($array, function ($current, $key) use (&$output) {
      $output[$key] = $current;
    });

    return $output;
  }
}