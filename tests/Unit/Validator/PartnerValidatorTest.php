<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 06.04.18
 * Time: 10:01
 */

namespace Tests\Unit\Validator;

use App\Base\Validator\PartnerValidator;
use Broker\Domain\Entity\Partner;
use Broker\System\BrokerInstance;
use PHPUnit\Framework\TestCase;

class PartnerValidatorTest extends TestCase
{
  protected $invalidValues;
  protected $instance;
  protected $partner;

  public function setUp()
  {
    $this->invalidValues = [
      [],
      new \stdClass(),
      null,
    ];
    $this->partner = new Partner();
    $this->instance = new PartnerValidator($this->createMock(BrokerInstance::class));
    $this->instance->setEntity($this->partner);
  }

  public function testValidateName()
  {
    $validValues = [
      'dima bilan',
      'OÜ Desperado Ehitus',
      'slava'

    ];

    $invalidValues = array_merge($this->invalidValues, [
      123123,
      234.12312,
      'aa'
    ]);

    foreach ($validValues as $value)
    {
      $this->instance->getEntity()->setName($value);
      $this->assertTrue($this->instance->validateName(), sprintf('Asserting that %s is VALID partner name', json_encode($value)));
    }

    foreach ($invalidValues as $value)
    {
      $this->instance->getEntity()->setName($value);
      $this->assertFalse($this->instance->validateName(), sprintf('Asserting that %s is INVALID partner name', json_encode($value)));
    }
  }

  public function testValidateIdentifier()
  {
    $validValues = [
      'AASA',
      'EMALENDUR',
      'SEXYBOY123'
    ];

    $invalidValues = array_merge($this->invalidValues, [
      12.322,
      'EMA LENDUR',
      'nocaps'
    ]);

    foreach ($validValues as $value)
    {
      $this->instance->getEntity()->setIdentifier($value);
      $this->assertTrue($this->instance->validateIdentifier(), sprintf('Asserting that %s is VALID partner identifier', json_encode($value)));
    }

    foreach ($invalidValues as $value)
    {
      $this->instance->getEntity()->setIdentifier($value);
      $this->assertFalse($this->instance->validateIdentifier(), sprintf('Asserting that %s is INVALID partner identifier', json_encode($value)));
    }
  }

  public function testValidateCommission()
  {
    $validValues = [
      0.25,
      2,
      99.99,
      '12',
      '10.23'
    ];

    $invalidValues = array_merge($this->invalidValues, [
      -12,
      100.2,
      'string',
      'any string'
    ]);

    foreach ($validValues as $value)
    {
      $this->instance->getEntity()->setCommission($value);
      $this->assertTrue($this->instance->validateCommission(), sprintf('Asserting that %s is VALID partner commission', json_encode($value)));
    }

    foreach ($invalidValues as $value)
    {
      $this->instance->getEntity()->setCommission($value);
      $this->assertFalse($this->instance->validateCommission(), sprintf('Asserting that %s is INVALID partner commission', json_encode($value)));
    }
  }

  public function testValidateStatus()
  {
    $validValues = [
      1,
      '0',
      '1',
      true,
      false
    ];

    $invalidValues = array_merge($this->invalidValues, [
      'some string',
      123.123
    ]);

    foreach ($validValues as $value)
    {
      $this->instance->getEntity()->setStatus($value);
      $this->assertTrue($this->instance->validateStatus(), sprintf('Asserting that %s is VALID partner status', json_encode($value)));
    }

    foreach ($invalidValues as $value)
    {
      $this->instance->getEntity()->setStatus($value);
      $this->assertFalse($this->instance->validateStatus(), sprintf('Asserting that %s is INVALID partner status', json_encode($value)));
    }
  }
}
