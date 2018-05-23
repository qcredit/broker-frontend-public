<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 11.04.18
 * Time: 10:41
 */

namespace Tests\Unit\Validator;

use App\Base\Validator\UserValidator;
use App\Model\UserForm;
use Broker\System\BaseTest;
use App\Model\User;
use Broker\System\BrokerInstance;
use Respect\Validation\Validator as V;

class UserValidatorTest extends BaseTest
{
  protected $invalidValues;
  protected $user;
  protected $instance;

  public function setUp()
  {
    $this->invalidValues = [
      [],
      new \stdClass(),
      null,
    ];
    $this->user = new User();
    $this->instance = new UserValidator($this->createMock(BrokerInstance::class));
    $this->instance->setEntity($this->user);
  }

  public function testValidateEmail()
  {
    $validValues = [
      'slava@bogu.ee',
      'vlad@putin.ru',
      'winteriscoming@holdthedoor.hodor',
      'queen@gov.co.uk'
    ];

    $invalidValues = array_merge($this->invalidValues, [
      'mine@ennu.',
      '@',
      'p@.p',
      '@peep.ee',
      123123,
      123.123
    ]);

    foreach ($validValues as $value)
    {
      $this->instance->getEntity()->setEmail($value);
      $this->assertTrue($this->instance->validateEmail(), sprintf('Asserting that %s is VALID email...', json_encode($value)));
    }

    foreach ($invalidValues as $value)
    {
      $this->instance->getEntity()->setEmail($value);
      $this->assertFalse($this->instance->validateEmail(), sprintf('Asserting that %s is INVALID email...', json_encode($value)));
    }
  }

  public function testValidateAccessLevel()
  {
    $validValues = array_keys(UserForm::ACCESS_LVL_LIST);

    $invalidValues = array_merge($this->invalidValues, [
      'yonigga',
      123.123,
      12,
      false,
      true,
      'true',
      'false'
    ]);

    foreach ($validValues as $value)
    {
      $this->instance->getEntity()->setAccessLevel($value);
      $this->assertTrue($this->instance->validateAccessLevel(), sprintf('Asserting that %s is VALID accessLevel...', json_encode($value)));
    }

    foreach ($invalidValues as $value)
    {
      $this->instance->getEntity()->setAccessLevel($value);
      $this->assertFalse($this->instance->validateAccessLevel(), sprintf('Asserting that %s is INVALID accessLevel...', json_encode($value)));
    }
  }
}
