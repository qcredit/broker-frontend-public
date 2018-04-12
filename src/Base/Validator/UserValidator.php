<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 11.04.18
 * Time: 10:40
 */

namespace App\Base\Validator;

use Broker\Domain\Service\Validator\AbstractEntityValidator;
use App\Model\UserForm;
use Respect\Validation\Validator as V;

class UserValidator extends AbstractEntityValidator
{
  /**
   * @var array
   */
  protected $validationAttributes = [
    UserForm::ATTR_EMAIL,
    UserForm::ATTR_ACCESS_LEVEL
  ];

  /**
   * @return bool
   */
  public function validateEmail()
  {
    $value = $this->getEntity()->getEmail();
    if (!V::stringType()->email()->validate($value))
    {
      $this->getEntity()->setErrors([UserForm::ATTR_EMAIL => 'Invalid email!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateAccessLevel()
  {
    $value = $this->getEntity()->getAccessLevel();
    if (!V::intVal()->notEmpty()->validate($value) || !in_array($value, array_keys(UserForm::ACCESS_LVL_LIST)))
    {
      $this->getEntity()->setErrors([UserForm::ATTR_ACCESS_LEVEL => 'Invalid access level!']);
      return false;
    }

    return true;
  }
}