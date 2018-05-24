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
use Broker\System\BrokerInstance;
use Broker\System\Log;
use Respect\Validation\Validator as V;
use Slim\Container;

class ContactValidator extends AbstractEntityValidator
{
  /**
   * @var array
   */
  protected $validationAttributes = [
    ContactForm::ATTR_NAME,
    ContactForm::ATTR_EMAIL,
    ContactForm::ATTR_MESSAGE,
    ContactForm::ATTR_RECAPTCHA
  ];

  /**
   * ContactValidator constructor.
   * @param BrokerInstance $instance
   */
  public function __construct(BrokerInstance $instance)
  {
    parent::__construct($instance);
  }

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

  /**
   * @return bool
   */
  public function validateGrecaptcharesponse()
  {
    $value = $this->getEntity()->getAttribute(ContactForm::ATTR_RECAPTCHA);

    if (!V::notEmpty()->validate($value) || !$this->verifyCaptcha())
    {
      $this->getEntity()->setErrors([ContactForm::ATTR_RECAPTCHA => _('Please verify you are a human!')]);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  protected function verifyCaptcha()
  {
    $data = http_build_query(
      [
        'secret' => '6LcXOVcUAAAAALmZc4LeyuThHko43K1qTBUluD0Y',
        'response' => $this->getEntity()->getAttribute(ContactForm::ATTR_RECAPTCHA),
        'remoteip' => $_SERVER['REMOTE_ADDR']
      ]
    );
    $options = ['http' =>
      [
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $data
      ]
    ];
    $context  = stream_context_create($options);
    $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);

    $result = json_decode($response);

    return $result->success === true;
  }
}