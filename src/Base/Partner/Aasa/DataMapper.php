<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 28.03.18
 * Time: 17:38
 */

namespace App\Base\Partner\Aasa;

use App\Model\ChooseOfferForm;
use Broker\Domain\Entity\AbstractEntity;
use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\PartnerRequest;
use Broker\Domain\Entity\PartnerResponse;
use Broker\Domain\Interfaces\Partner\SchemaInterface;
use Broker\Domain\Interfaces\PartnerDataMapperInterface;
use App\Model\ApplicationForm;
use App\Model\OfferForm;
use Broker\Domain\Interfaces\System\Delivery\DeliveryHeadersInterface;
use Broker\Domain\Interfaces\System\Delivery\DeliveryOptionsInterface;
use Broker\System\Delivery\DeliveryHeaders;
use Broker\System\Delivery\DeliveryOptions;
use Broker\System\Error\InvalidConfigException;

class DataMapper implements PartnerDataMapperInterface
{
  const STATUS_IN_PROCESS = 'InProcess';
  const STATUS_ACCEPTED = 'Accepted';
  const STATUS_REJECTED = 'Rejected';
  const STATUS_SIGNED = 'Signed';
  const STATUS_WAITING_PAID_OUT = 'WaitingPaidOut';
  const STATUS_IN_DEBT = 'InDebt';
  const STATUS_ACTIVE = 'Active';
  const STATUS_PAID_BACK = 'PaidBack';
  const STATUS_OK = 'OK';

  protected $configFile = 'aasa.config.json';

  /**
   * @return array
   * @throws InvalidConfigException
   * @throws \Exception
   */
  public function getRequestSchema(): array
  {
    return $this->getDecodedConfigFile()['requestSchema'];
  }

  /**
   * @return SchemaInterface
   */
  public function getFormSchema(): SchemaInterface
  {
    return new FormSchema();
  }

  /**
   * @return array
   * @throws InvalidConfigException
   * @throws \Exception
   */
  public function getConfig(): array
  {
    return $this->getDecodedConfigFile()['config'];
  }

  /**
   * @return bool|string
   * @throws InvalidConfigException
   * @throws \Exception
   */
  public function getConfigFile()
  {
    $file = sprintf('%s/%s', dirname(__FILE__), $this->configFile);
    if (!file_exists($file))
    {
      throw new InvalidConfigException('No configuration file found for Aasa!');
    }

    return file_get_contents($file);
  }

  /**
   * @return mixed
   * @throws InvalidConfigException
   * @throws \Exception
   */
  public function getDecodedConfigFile()
  {
    return json_decode($this->getConfigFile(), true);
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
      'id' => OfferForm::ATTR_REMOTE_ID,
      'amount' => OfferForm::ATTR_LOAN_AMOUNT,
      'period' => OfferForm::ATTR_LOAN_TERM,
      'interest' => OfferForm::ATTR_INTEREST,
      'avg' => OfferForm::ATTR_MONTHLY_FEE,
      'apr' => OfferForm::ATTR_APR,
      'acceptancePageUrl' => OfferForm::ATTR_ACCEPTANCE_URL,
      'esignUrl' => OfferForm::ATTR_ESIGN_URL
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
        if ($this->hasEnumMapping($item))
        {
          $item = $this->getEnumMappings()[$item][$data->getAttribute($item)];
        }
        else
        {
          $item = $data->getAttribute($item);
        }
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

  public function mapErrorsToForm(array $errors): array
  {
    $flat = $this->flattenArray($this->getRequestPayload());

    $mapped = [];
    foreach ($errors as $key => $value)
    {
      if (isset($flat[$key]))
      {
        $mapped[$flat[$key]] = $value;
      }
      else
      {
        $mapped[$key] = $value;
      }
    }

    return $mapped;
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
    else if ($data['data']['status'] === self::STATUS_PAID_BACK)
    {
      $data['paidBackDate'] = new \DateTime();
    }
    else if ($data['data']['status'] === self::STATUS_ACTIVE)
    {
      $data['paidOutDate'] = new \DateTime();
    }
    else if ($data['data']['status'] === self::STATUS_ACCEPTED)
    {
      $data['acceptedDate'] = new \DateTime();
    }
    else if (in_array($data['data']['status'], [self::STATUS_SIGNED, self::STATUS_WAITING_PAID_OUT]))
    {
      $data['chosenDate'] = new \DateTime();
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
  public function getResponseErrors(PartnerResponse $response): array
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

        $field = $this->getFormattedField($row['field']);
        $message = $this->getFormattedMessage($row['message'], $row['field']);

        if (isset($map[$field]))
        {
          $errors[$map[$field]] = $message;
        }
        else
        {
          $errors[$field] = $message;
        }
      }
    }

    return $errors;
  }

  /**
   * @param string $field
   * @return mixed|string
   */
  protected function getFormattedField(string $field)
  {
    $parts = explode('.', $field);
    if ($parts)
    {
      return end($parts);
    }
    else {
      return $field;
    }
  }

  protected function getFormattedMessage(string $message, string $field)
  {
    $formattedField = $this->getFormattedField($field);
    if (strpos($message, $field) !== false)
    {
      $message = str_replace($field, $formattedField, $message);
    }

    return $this->beautifyErrorMessage($message);
  }

  /**
   * @param $message
   * @return string
   */
  protected function beautifyErrorMessage($message)
  {
    if (strpos($message, 'Does not have a value in the enumeration') !== false)
    {
      return _('Please provide a value in provided range.');
    }

    if (strpos($message, 'Does not match the regex pattern') !== false)
    {
      return _('Invalid format provided.');
    }

    if (preg_match('/(\w+)\smust\shave\sa\slength\sbetween\s(\d+)\sand\s(\d+)$/', $message, $matches))
    {
      return sprintf(_('Must have a length between %d and %d characters'), $matches[2], $matches[3]);
    }

/*    if (strpos($message, 'must have a length between') !== false)
    {
      return _('Must have a length between X and Y');
    }*/

    return $message;
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
        'message' => _('Contract already signed!')
      ],
      1126 => [
        'field' => ChooseOfferForm::ATTR_FIRST_PAYMENT_DATE,
        'message' => _('Invalid payment date!')
      ],
      1127 => [
        'field' => ChooseOfferForm::ATTR_SIGN_METHOD,
        'message' => _('Signing method not allowed!')
      ]
    ];
  }

  /**
   * @return array
   * @throws InvalidConfigException
   * @throws \Exception
   */
  public function getIncomingUpdateSchema(): array
  {
    return $this->getDecodedConfigFile()['incomingUpdateSchema'];
  }

  /**
   * @param PartnerResponse $partnerResponse
   * @return array
   */
  public function getIncomingUpdateResponse(PartnerResponse $partnerResponse)
  {
    if (!$partnerResponse->isOk())
    {
      $errors = $partnerResponse->getErrors();

      return [
        'updateResponse' => [
          'status' => 'ERROR',
          'message' => !empty($errors) ? $errors[0] : 'Unknown error'
        ]
      ];
    }

    return [
      'updateResponse' => [
        'status' => 'OK'
      ]
    ];
  }

  /**
   * @param PartnerResponse $partnerResponse
   * @return mixed
   */
  public function extractRemoteOfferId(PartnerResponse $partnerResponse)
  {
    return json_decode($partnerResponse->getResponseBody(), true)['update']['id'];
  }

  public function getEnumMappings()
  {
    return [
      ApplicationForm::ATTR_EDUCATION => [
        ApplicationForm::ENUM_EDUCATION_MD => 'Other',
        ApplicationForm::ENUM_EDUCATION_MAs => 'MSc',
        ApplicationForm::ENUM_EDUCATION_MBA => 'MSc',
        ApplicationForm::ENUM_EDUCATION_MA => 'MSc',
        ApplicationForm::ENUM_EDUCATION_BA => 'BA',
        ApplicationForm::ENUM_EDUCATION_VOCATIONAL => 'Vocational',
        ApplicationForm::ENUM_EDUCATION_SECONDARY => 'Secondary',
        ApplicationForm::ENUM_EDUCATION_BASIC => 'Basic',
        ApplicationForm::ENUM_EDUCATION_OTHER => 'Other'
      ],
      ApplicationForm::ATTR_INCOME_SOURCE => [
        ApplicationForm::ENUM_INCOME_EMPLOYED => 'Employed',
        ApplicationForm::ENUM_INCOME_STUDENT => 'Student',
        ApplicationForm::ENUM_INCOME_PENSION => 'NormalPension',
        ApplicationForm::ENUM_INCOME_DISABILITY => 'DisabilityPension',
        ApplicationForm::ENUM_INCOME_UNEMPLOYED => 'Unemployed',
        ApplicationForm::ENUM_INCOME_ALIMONY => 'BenefitOrAlimony',
        ApplicationForm::ENUM_INCOME_SELF_EMPLOYED => 'SelfEmployed',
        ApplicationForm::ENUM_INCOME_FARMER => 'Farmer',
        ApplicationForm::ENUM_INCOME_OTHER => 'Other'
      ],
      ApplicationForm::ATTR_MARITAL_STATUS => [
        ApplicationForm::ENUM_MARITAL_SINGLE => 'Single',
        ApplicationForm::ENUM_MARITAL_MARRIED => 'Married',
        ApplicationForm::ENUM_MARITAL_MARRIED_DIVORCING => 'MarriedDivorcing',
        ApplicationForm::ENUM_MARITAL_DIVORCED => 'Divorced',
        ApplicationForm::ENUM_MARITAL_SEPARATED => 'Separated',
        ApplicationForm::ENUM_MARITAL_WIDOW => 'Widow',
        ApplicationForm::ENUM_MARITAL_OTHER => 'Other'
      ],
      ApplicationForm::ATTR_PAYOUT_METHOD => [
        ApplicationForm::ENUM_PAYOUT_GIRO => 'Giro',
        ApplicationForm::ENUM_PAYOUT_BLUECASH => 'BlueCash',
        ApplicationForm::ENUM_PAYOUT_ACCOUNT => 'Account'
      ],
      ApplicationForm::ATTR_ACCOUNT_TYPE => [
        ApplicationForm::ENUM_ACCOUNT_PERSONAL => 'Personal',
        ApplicationForm::ENUM_ACCOUNT_JOINT => 'Joint',
        ApplicationForm::ENUM_ACCOUNT_COMPANY => 'Company'
      ],
      ApplicationForm::ATTR_RESIDENTIAL_TYPE => [
        ApplicationForm::ENUM_RESIDENCY_OWN => 'Own',
        ApplicationForm::ENUM_RESIDENCY_RENT => 'Rented',
        ApplicationForm::ENUM_RESIDENCY_FAMILY => 'LivingWithFamily',
        ApplicationForm::ENUM_RESIDENCY_SOCIAL => 'HousingAssociation',
        ApplicationForm::ENUM_RESIDENCY_OTHER => 'Other'
      ],
      ApplicationForm::ATTR_PROPERTY_TYPE => [
        ApplicationForm::ENUM_PROPERTY_FLAT => 'Apartment',
        ApplicationForm::ENUM_PROPERTY_HOUSE => 'House',
        ApplicationForm::ENUM_PROPERTY_DUPLEX => 'Duplex',
        ApplicationForm::ENUM_PROPERTY_OTHER => 'Other'
      ],
      ApplicationForm::ATTR_LOAN_PURPOSE => [
        null => '',
        ApplicationForm::ENUM_PURPOSE_BILLS => 'Bills',
        ApplicationForm::ENUM_PURPOSE_VACATION => 'Vacation',
        ApplicationForm::ENUM_PURPOSE_MORTGAGE => 'RentOrMortgage',
        ApplicationForm::ENUM_PURPOSE_CAR => 'Car',
        ApplicationForm::ENUM_PURPOSE_ENTERTAINMENT => 'Entertainment',
        ApplicationForm::ENUM_PURPOSE_GROCERIES => 'Groceries',
        ApplicationForm::ENUM_PURPOSE_RENOVATION => 'Renovation',
        ApplicationForm::ENUM_PURPOSE_ELECTRONICS => 'Electronics',
        ApplicationForm::ENUM_PURPOSE_FURNITURE => 'Furniture',
        ApplicationForm::ENUM_PURPOSE_SCHOOL => 'School',
        ApplicationForm::ENUM_PURPOSE_TAX => 'TaxPayment',
        ApplicationForm::ENUM_PURPOSE_INVESTMENT => 'Investment',
        ApplicationForm::ENUM_PURPOSE_INVOICE => 'InvoicePayment',
        ApplicationForm::ENUM_PURPOSE_RENT => 'Rent',
        ApplicationForm::ENUM_PURPOSE_OTHER => 'Other'
      ]
    ];
  }

  /**
   * @param string $field
   * @return bool
   */
  protected function hasEnumMapping(string $field)
  {
    return array_key_exists($field, $this->getEnumMappings());
  }

  /**
   * @param PartnerRequest $request
   * @return DeliveryHeadersInterface
   */
  public function getDeliveryHeaders(PartnerRequest $request): DeliveryHeadersInterface
  {
    $headers = new DeliveryHeaders();
    $headers->addHeader('Accept', 'application/json')
      ->addHeader('Content-Type', 'application/json')
      ->addHeader('Authorization', sprintf('Basic %s', base64_encode(sprintf('%s:%s', $request->getPartner()->getRemoteUsername(), $request->getPartner()->getRemotePassword()))));

    if ($request->getType() === PartnerRequest::REQUEST_TYPE_UPDATE)
    {
      $headers->addHeader('X-Auth-Token', $request->getOffer()->getAttribute('token'));
    }

    return $headers;
  }

  /**
   * @param PartnerRequest $request
   * @return DeliveryOptionsInterface
   */
  public function getDeliveryOptions(PartnerRequest $request): DeliveryOptionsInterface
  {
    $options = new DeliveryOptions();

    $apiUrl = getenv('ENV_TYPE') == 'production' ? $request->getPartner()->getApiLiveUrl() : $request->getPartner()->getApiTestUrl();

    $options->addOption(CURLOPT_URL, $apiUrl)
      ->addOption(CURLOPT_RETURNTRANSFER, true)
      ->addOption(CURLOPT_CONNECTTIMEOUT, 30)
      ->addOption(CURLOPT_SSL_VERIFYPEER, false);

    if ($request->getType() === PartnerRequest::REQUEST_TYPE_INITIAL)
    {
      $options->addOption(CURLOPT_POSTFIELDS, $request->getRequestPayload());
    }
    else if ($request->getType() === PartnerRequest::REQUEST_TYPE_UPDATE)
    {
      $options->addOption(CURLOPT_URL, $apiUrl . "/" . $request->getOffer()->getRemoteId());
    }

    return $options;
  }
}