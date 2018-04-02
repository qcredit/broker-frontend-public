<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 28.03.18
 * Time: 17:38
 */

namespace App\Base;

use Broker\Domain\Entity\Application;
use Broker\Domain\Interfaces\PartnerDataMapperInterface;

class AasaDataMapper implements PartnerDataMapperInterface
{
  public function getRequestSchema(): string
  {
    return file_get_contents(dirname(__FILE__) . '/Config/aasa.schema.json');
  }

  public function mapAppToRequest(Application $application)
  {
    $object = [
      "income" => [
        "sourceType" => $application->getDataElement('incomeSourceType'),
        'netPerMonth' => $application->getDataElement('netPerMonth'),
        'employerName' => $application->getDataElement('employerName'),
        'position' => $application->getDataElement('position'),
        'trade' => $application->getDataElement('trade'),
        'yearSince' => $application->getDataElement('yearSince'),
        'monthSince' => $application->getDataElement('monthSince'),
        'currentStudy' => $application->getDataElement('currentStudy')
      ],
      'loanPurposeType' => $application->getDataElement('loanPurposeType'),
      'pin' => $application->getDataElement('pin'),
      'contactAddress' => [
        'street' => $application->getDataElement('street'),
        'postalCode' => $application->getDataElement('postalCode'),
        'houseNumber' => $application->getDataElement('houseNr'),
        'apartmentNumber' => $application->getDataElement('apartmentNr'),
        'city' => $application->getDataElement('city')
      ],
      'mainAddress' => [
        'street' => $application->getDataElement('street'),
        'postalCode' => $application->getDataElement('postalCode'),
        'houseNumber' => $application->getDataElement('houseNr'),
        'apartmentNumber' => $application->getDataElement('apartmentNr'),
        'city' => $application->getDataElement('city')
      ],
      'account' => [
        'accountNumber' => $application->getDataElement('accountNumber'),
        'accountType' => $application->getDataElement('accountType'),
        'accountHolder' => $application->getDataElement('accountHolder')
      ],
      'idCardNumber' => $application->getDocumentNr(),
      'mobilePhoneType' => $application->getDataElement('mobilePhoneType'),
      'payoutMethod' => $application->getDataElement('payoutMethod'),
      'educationType' => $application->getDataElement('educationType'),
      'maritalStatusType' => $application->getDataElement('maritalStatusType'),
      'housing' => [
        'residentialType' => $application->getDataElement('residentialType'),
        'propertyType' => $application->getDataElement('propertyType'),
      ],
      'loanAmount' => $application->getLoanAmount(),
      'loanPeriodInMonths' => $application->getLoanTerm(),
      'firstName' => $application->getFirstName(),
      'lastName' => $application->getLastName(),
      'emailAddress' => $application->getEmail(),
      'mobilePhoneNumber' => $application->getPhone(),
      'eMarketing' => 'Y',
      'pMarketing' => 'Y',
      'tMarketing' => 'Y'
    ];

    return json_encode($object);
  }
}