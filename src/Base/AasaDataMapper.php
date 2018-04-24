<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 28.03.18
 * Time: 17:38
 */

namespace App\Base;

use App\Model\ChooseOfferForm;
use Broker\Domain\Entity\AbstractEntity;
use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\PartnerRequest;
use Broker\Domain\Entity\PartnerResponse;
use Broker\Domain\Interfaces\PartnerDataMapperInterface;
use App\Model\ApplicationForm;
use Broker\System\Error\InvalidConfigException;

class AasaDataMapper implements PartnerDataMapperInterface
{
  const STATUS_IN_PROCESS = 'InProcess';
  const STATUS_ACTIVE = 'Active';
  const STATUS_PAID_BACK = 'PaidBack';
  const STATUS_IN_DEBT = 'InDebt';
  const STATUS_REJECTED = 'Rejected';
  const STATUS_ACCEPTED = 'Accepted';
  const STATUS_OK = 'OK';

  protected $configFile = 'aasa.config.json';

  /**
   * @return array
   * @throws InvalidConfigException
   * @throws \Exception
   */
  public function getRequestSchema(): array
  {
    return json_decode($this->getConfigFile(), true)['requestSchema'];
  }

  /**
   * @return array
   * @throws InvalidConfigException
   * @throws \Exception
   */
  public function getConfig(): array
  {
    return json_decode($this->getConfigFile(), true)['config'];
  }

  /**
   * @return bool|string
   * @throws InvalidConfigException
   * @throws \Exception
   */
  public function getConfigFile()
  {
    $file = dirname(__FILE__) . '/Config/aasa.config.json';
    if (!file_exists($file))
    {
      throw new InvalidConfigException('No configuration file found for Aasa!');
    }

    return file_get_contents($file);
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
  public function getChooseOfferPayload()
  {
    return [
      'signingMethod' => ChooseOfferForm::ATTR_SIGN_METHOD,
      'update' => [
        'firstPaymentDate' => ChooseOfferForm::ATTR_FIRST_PAYMENT_DATE
      ]
    ];
  }

  /**
   * @return array
   */
  public function getResponsePayload()
  {
    return [
      'id' => 'remoteId',
      'amount' => 'loanAmount',
      'period' => 'loanTerm',
      'interest' => 'interest',
      'avg' => 'monthlyFee'
    ];
  }

  /**
   * @param $item
   * @param $key
   * @param $data
   */
  public function mapper(&$item, $key, $data)
  {
    if (is_array($item))
    {
      array_walk($item, [$this, 'mapper'], $data);
    }
    else {
      if ($data instanceof AbstractEntity)
      {
        $item = $data->getAttribute($item);
      }
      else
      {
        $item = $data[$item];
      }
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
   * @param array $data
   * @param PartnerRequest $request
   * @return string
   */
  public function mapDataToRequest(array $data, PartnerRequest $request)
  {
    if ($request->getType() === PartnerRequest::REQUEST_TYPE_CHOOSE)
    {
      $payload = $this->getChooseOfferPayload();
    }

    array_walk($payload, [$this, 'mapper'], $data);

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
    if (isset($data['data']['status']))
    {
      $this->updateStatus($data, $response);
    }

    return $data;
  }

  /**
   * @param array $data
   * @param PartnerResponse $response
   * @return array
   */
  protected function updateStatus(array &$data, PartnerResponse $response)
  {
    if ($response->getType() === PartnerRequest::REQUEST_TYPE_CHOOSE && $data['data']['status'] === self::STATUS_OK)
    {
      $data['chosenDate'] = new \DateTime();
    }
    else if ($data['data']['status'] === self::STATUS_ACCEPTED)
    {
      $data['acceptedDate'] = new \DateTime();
    }
    else if ($data['data']['status'] === self::STATUS_REJECTED)
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
    $body = json_decode($response->getResponseBody(), true);

    if (is_array($body) && isset($body['errors']))
    {
      $errors = $this->getBodyErrors($body);
    }
    else
    {
      $errors = $this->getFieldErrors($body);
    }

    return $errors;
  }

  /**
   * @param array $body
   * @return array
   */
  protected function getBodyErrors(array $body)
  {
    $errors = [];

    foreach ($body['errors'] as $error)
    {
      if ($this->isApiErrorDefined($error['code']))
      {
        $err = $this->mapApiError($error['code']);
        $errors[$err['field']] = $err['message'];
      }
      else {
        $errors[] = $error['message'];
      }
    }

    return $errors;
  }

  /**
   * @param array $body
   * @return array
   */
  protected function getFieldErrors(array $body)
  {
    $errors = [];
    foreach ($body as $row)
    {
      if (is_array($row) && isset($row['field']))
      {
        $map = $this->flattenArray($this->getRequestPayload());

        if (isset($map[$row['field']]))
        {
          $errors[$map[$row['field']]] = $row['message'];
        }
        else
        {
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

    if (is_array($body))
    {
      if (isset($body['errors']))
      {
        return true;
      }

      foreach ($body as $row)
      {
        if (is_array($row) && isset($row['code']))
        {
          return true;
        }
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

  /**
   * @return array
   * @throws InvalidConfigException
   * @throws \Exception
   */
  public function getChooseRequestSchema(): array
  {
    return json_decode($this->getConfigFile(), true)['chooseRequestSchema'];
  }

  /**
   * @param $code
   * @return bool
   */
  protected function isApiErrorDefined($code): bool
  {
    return array_key_exists($code, $this->definedApiErrors());
  }

  /**
   * @param $code
   * @return mixed
   */
  protected function mapApiError($code)
  {
    return $this->definedApiErrors()[$code];
  }

  /**
   * @return array
   */
  protected function definedApiErrors()
  {
    return [
      1125 => [
        'field' => 'general',
        'message' => 'Contract already signed!'
      ],
      1126 => [
        'field' => ChooseOfferForm::ATTR_FIRST_PAYMENT_DATE,
        'message' => 'Invalid payment date!'
      ],
      1127 => [
        'field' => ChooseOfferForm::ATTR_SIGN_METHOD,
        'message' => 'Signing method not allowed!'
      ]
    ];
  }
}