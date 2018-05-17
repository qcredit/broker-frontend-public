<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 17.05.18
 * Time: 13:26
 */

namespace App\Base\Validator;

use App\Model\ContactForm;
use Broker\Domain\Service\Validator\AbstractEntityValidator;
use Respect\Validation\Validator as V;

class ContactValidator extends AbstractEntityValidator
{
  /**
   * @var array
   */
  protected $validationAttributes = [
    ContactForm::ATTR_NAME,
    ContactForm::ATTR_EMAIL,
    ContactForm::ATTR_MESSAGE
  ];

  /**
   * @return bool
   */
  public function validateName()
  {
    $value = $this->getEntity()->getName();
    if (!V::stringType()->notEmpty()->validate($value))
    {
      $this->getEntity()->setErrors([ContactForm::ATTR_NAME => _('Invalid name!')]);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateEmail()
  {
    $value = $this->getEntity()->getEmail();
    if (!V::stringType()->regex('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,8}$/')->validate($value))
    {
      $this->getEntity()->setErrors([ContactForm::ATTR_EMAIL => _('Invalid email!')]);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateMessage()
  {
    $value = $this->getEntity()->getMessage();
    if (!V::stringType()->validate($value))
    {
      $this->getEntity()->setErrors([ContactForm::ATTR_MESSAGE => _('Invalid message!')]);
      return false;
    }
    else if (!V::length(10)->validate($value))
    {
      $this->getEntity()->setErrors([ContactForm::ATTR_MESSAGE => _('Message has to contain at least 10 characters')]);
      return false;
    }

    return true;
  }
}