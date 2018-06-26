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
  protected $rawData;
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

  /**
   * @return bool
   */
  public function validateFeatured()
  {
    if (!V::boolVal()->validate($this->getEntity()->getFeatured()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_FEATURED => 'Invalid value!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateEmail()
  {
    if (!V::email()->validate($this->getEntity()->getEmail()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_EMAIL => 'Invalid e-mail!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateWebsite()
  {
    if (!V::url()->validate($this->getEntity()->getWebsite()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_WEBSITE => 'Invalid website!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateApiEnabled()
  {
    if (!V::boolVal()->validate($this->getEntity()->getApiEnabled()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_API_ENABLED => 'Invalid value!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateApiLiveUrl()
  {
    if (!V::url()->validate($this->getEntity()->getApiLiveUrl()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_API_LIVE_URL => 'Invalid API live URL!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateApiTestUrl()
  {
    if (!V::url()->validate($this->getEntity()->getApiTestUrl()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_API_TEST_URL => 'Invalid API test URL!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateRemoteUsername()
  {
    if (!V::stringType()->validate($this->getEntity()->getRemoteUsername()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_REMOTE_USERNAME => 'Invalid remote username!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateRemotePassword()
  {
    if (!V::stringType()->validate($this->getEntity()->getRemotePassword()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_REMOTE_PASSWORD => 'Invalid remote password!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateLocalUsername()
  {
    if (!V::stringType()->validate($this->getEntity()->getLocalUsername()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_LOCAL_USERNAME => 'Invalid local username!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateLocalPassword()
  {
    if (!V::stringType()->validate($this->getEntity()->getLocalPassword()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_LOCAL_PASSWORD => 'Invalid local password!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateAuthorizationType()
  {
    if (!in_array($this->getEntity()->getAuthorizationType(), PartnerForm::AUTHORIZATION_LIST))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_AUTHORIZATION_TYPE => 'Invalid authorization type!']);
      return false;
    }

    return true;
  }

  /**
   * @return bool
   */
  public function validateLogoUrl()
  {
    if (!V::stringType()->validate($this->getEntity()->getLogoUrl()))
    {
      $this->getEntity()->setErrors([PartnerForm::ATTR_LOGO_URL => 'Invalid logo URL!']);
      return false;
    }

    return true;
  }

  /**
   * @return array
   * @codeCoverageIgnore
   */
  public function getRawData()
  {
    return $this->rawData;
  }

  /**
   * @param array $rawData
   * @return PartnerValidator
   * @codeCoverageIgnore
   */
  public function setRawData(array $rawData)
  {
    $this->rawData = $rawData;
    return $this;
  }
}