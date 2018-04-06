<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 06.04.18
 * Time: 09:39
 */

namespace App\Base\Validator;

use App\Model\PartnerForm;
use Broker\Domain\Service\Validator\AbstractEntityValidator;
use Respect\Validation\Validator as V;

class PartnerValidator extends AbstractEntityValidator
{
  /**
   * @var array
   */
  protected $validationAttributes = [
    PartnerForm::ATTR_NAME,
    PartnerForm::ATTR_IDENTIFIER,
    PartnerForm::ATTR_COMMISSION,
    PartnerForm::ATTR_STATUS
  ];

  /**
   * @return bool
   */
  public function validateName()
  {
    if (!V::stringType()->notEmpty()->length(3)->validate($this->getEntity()->getName()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_NAME => 'Invalid partner name!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateIdentifier()
  {
    if (!V::alnum()->noWhitespace()->uppercase()->notEmpty()->length(3, 20)->validate($this->getEntity()->getIdentifier()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_IDENTIFIER => 'Invalid partner identifier!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateCommission()
  {
    if (!V::floatVal()->min(0)->max(100)->positive()->validate($this->getEntity()->getCommission()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_COMMISSION => 'Invalid commission!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateStatus()
  {
    if (!V::boolVal()->noneOf(V::nullType())->validate($this->getEntity()->getStatus()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_STATUS => 'Invalid status!']);
      return false;
    }

    return true;
  }
}