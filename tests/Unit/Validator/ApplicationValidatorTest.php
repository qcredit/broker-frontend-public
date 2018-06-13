<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 06.06.18
 * Time: 11:30
 */

namespace Tests\Unit\Validator;

use App\Base\Validator\ApplicationValidator;
use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\Partner;
use Broker\Domain\Interfaces\PartnerDataMapperInterface;
use Broker\Domain\Interfaces\SchemaValidatorInterface;
use Broker\System\BaseTest;
use Tests\Helpers\LoggerMockTrait;

class ApplicationValidatorTest extends BaseTest
{
  use LoggerMockTrait;

  protected $mock;
  protected $altMock;
  protected $partners;
  protected $validatorMock;

  public function setUp()
  {
    $this->setupMocks();
    $this->mock = $this->getMockBuilder(ApplicationValidator::class)
      ->disableOriginalConstructor()
      ->setMethods(['getEntity', 'getPartners', 'validatePartnerRequirements', 'validateAttributes', 'getSchemaValidator', 'getLogger'])
      ->getMock();
    $this->mock->method('getLogger')
      ->willReturn($this->loggerMock);
    $this->altMock = $this->getMockBuilder(ApplicationValidator::class)
      ->disableOriginalConstructor()
      ->setMethods(['validatePartnerRequirements', 'getLogger'])
      ->getMock();
    $this->altMock->method('getLogger')
      ->willReturn($this->loggerMock);
    $this->partners = [
      (new Partner())->setDataMapper($this->createMock(PartnerDataMapperInterface::class)),
      (new Partner())->setDataMapper($this->createMock(PartnerDataMapperInterface::class))
    ];
    $this->validatorMock = $this->createMock(SchemaValidatorInterface::class);
  }

  public function testValidateWithNoPartners()
  {
    $this->mock->method('getPartners')
      ->willReturn([]);
    $this->mock->method('getEntity')
      ->willReturn((new Application()));

    $this->assertTrue($this->mock->validate());
  }

  public function testValidateWithApplicationErrors()
  {
    $this->mock->method('getPartners')
      ->willReturn([]);
    $this->mock->method('getEntity')
      ->willReturn((new Application())->setErrors(['attribute' => 'error']));

    $this->assertFalse($this->mock->validate());
  }

  public function testValidateWithPartners()
  {
    $this->mock->method('getEntity')
      ->willReturn((new Application()));

    $this->mock->method('getPartners')
      ->willReturn($this->partners);

    $this->mock->expects($this->exactly(count($this->partners)))
      ->method('validatePartnerRequirements');

    $this->assertTrue($this->mock->validate());
  }

  public function testValidateWithoutAttributes()
  {
    $this->mock->method('getEntity')
      ->willReturn((new Application()));

    $this->mock->method('getPartners')
      ->willReturn($this->partners);

    $this->mock->expects($this->exactly(count($this->partners)))
      ->method('validatePartnerRequirements');

    $this->mock->expects($this->never())
      ->method('validateAttributes');

    $this->assertTrue($this->mock->validate());
  }

  public function testValidateWithAttributes()
  {
    $this->mock->setValidationAttributes([
      'size',
      'length'
    ]);

    $this->mock->expects($this->once())
      ->method('validateAttributes')
      ->willReturn(true);
    $this->mock->method('getPartners')
      ->willReturn([]);

    $this->assertTrue($this->mock->validate());
  }

  public function testValidateWithAttributesReturnsFalse()
  {
    $this->mock->setValidationAttributes([
      'size',
      'length'
    ]);

    $this->mock->expects($this->once())
      ->method('validateAttributes')
      ->willReturn(false);
    $this->mock->method('getPartners')
      ->willReturn([]);
    $this->mock->method('getEntity')
      ->willReturn((new Application()));

    $this->assertFalse($this->mock->validate());
  }

  public function validatePartnerRequirements()
  {
    $this->validatorMock->method('isValid')
      ->willReturn(true);
    $this->mock->method('getSchemaValidator')
      ->willReturn($this->validatorMock);

    $partner = $this->partners[0];
    $this->assertTrue($this->invokeMethod($this->mock, 'validatePartnerRequirements', [$partner]));
  }

  public function validatePartnerRequirementsFail()
  {
    $errors = ['attribute' => 'error'];
    $this->validatorMock->method('isValid')
      ->willReturn(false);
    $this->validatorMock->method('getErrors')
      ->willReturn($errors);
    $this->mock->method('getSchemaValidator')
      ->willReturn($this->validatorMock);

    $partner = $this->partners[0];
    $this->assertFalse($this->invokeMethod($this->mock, 'validatePartnerRequirements', [$partner]));
    $this->assertSame($errors, $this->mock->getEntity()->getErrors());
  }

  public function testValidateAttributes()
  {
    $this->altMock->setValidationAttributes([
      'color',
      'size'
    ]);
    $this->altMock->setEntity((new Application())->setErrors(['color' => 'wrong', 'size' => 'too large', 'name' => 'invalid']));

    $this->assertFalse($this->altMock->validateAttributes());
  }

  public function testValidateAttributesDeletesOtherErrors()
  {
    $this->altMock->setValidationAttributes([
      'color',
      'size'
    ]);
    $this->altMock->setEntity((new Application())->setErrors(['color' => 'wrong', 'size' => 'too large', 'name' => 'invalid']));

    $this->assertFalse($this->altMock->validateAttributes());
    $this->assertSame(['color' => 'wrong', 'size' => 'too large'], $this->altMock->getEntity()->getErrors());
  }
}
