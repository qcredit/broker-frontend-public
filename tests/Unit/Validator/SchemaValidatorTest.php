<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 17.04.18
 * Time: 09:43
 */

namespace Tests\Unit\Validator;

use App\Base\Validator\SchemaValidator;
use Broker\System\BaseTest;
use JsonSchema\Validator;
use PHPUnit\Framework\TestCase;

class SchemaValidatorTest extends BaseTest
{
  protected $validatorMock;
  protected $mock;

  public function setUp()
  {
    $this->validatorMock = $this->getMockBuilder(Validator::class)
      ->disableOriginalConstructor()
      ->setMethods(['getErrors', 'validate'])
      ->getMock();

    $this->mock = $this->getMockBuilder(SchemaValidator::class)
      ->disableOriginalConstructor()
      ->setMethods(['getValidator'])
      ->getMock();
  }

  public function testFormatErrors()
  {
    $errors = [
      [
        'property' => 'some.property',
        'message' => 'Dis is not right!'
      ],
      [
        'property' => 'normal',
        'message' => 'Maybe dis is right'
      ]
    ];

    $this->validatorMock->expects($this->once())
      ->method('getErrors')
      ->willReturn($errors);

    $this->mock->expects($this->once())
      ->method('getValidator')
      ->willReturn($this->validatorMock);

    $result = $this->invokeMethod($this->mock, 'getFormattedErrors', []);

    $this->assertTrue(is_array($result));
    $this->assertNotSame($errors, $result);
    $this->assertSame('property', array_keys($result)[0]);
    $this->assertSame('normal', array_keys($result)[1]);
  }

  public function testBeautifyErrorMessage()
  {
    $message = 'Does not have a value in the enumeration ["PostPaid","PrePaid","Mix"]';

    $result = $this->invokeMethod($this->mock, 'beautifyErrorMessage', [$message]);

    $this->assertSame('Please provide a value in provided range.', $result);
  }

  public function testBeautifyErrorMessageNotAltered()
  {
    $message = 'Some random error stating that you owe me money!';

    $result = $this->invokeMethod($this->mock, 'beautifyErrorMessage', [$message]);

    $this->assertSame($message, $result);
  }

  public function testGetErrors()
  {
    $mock = $this->getMockBuilder(SchemaValidator::class)
      ->disableOriginalConstructor()
      ->setMethods(['getFormattedErrors'])
      ->getMock();
    $mock->expects($this->once())
      ->method('getFormattedErrors')
      ->willReturn([]);

    $this->assertTrue(is_array($mock->getErrors()));
  }

  public function testValidate()
  {
    $data = 'sasdas';
    $schema = 'sadasd';
    $this->validatorMock->expects($this->once())
      ->method('validate')
      ->willReturn(true);
    $this->mock->expects($this->once())
      ->method('getValidator')
      ->willReturn($this->validatorMock);

    $this->assertTrue($this->mock->validate($data,$schema));
  }

  public function testValidateFalse()
  {
    $data = 'sasdas';
    $schema = 'sadasd';
    $this->validatorMock->expects($this->once())
      ->method('validate')
      ->willReturn(false);
    $this->mock->expects($this->once())
      ->method('getValidator')
      ->willReturn($this->validatorMock);

    $this->assertFalse($this->mock->validate($data,$schema));
  }
}
