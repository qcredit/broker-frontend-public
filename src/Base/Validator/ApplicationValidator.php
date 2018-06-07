<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 06.06.18
 * Time: 11:26
 */

namespace App\Base\Validator;

use Broker\Domain\Entity\AbstractEntity;
use Broker\Domain\Entity\Partner;
use Broker\Domain\Interfaces\EntityValidatorInterface;
use Broker\Domain\Interfaces\Repository\PartnerRepositoryInterface;
use Broker\Domain\Interfaces\SchemaValidatorInterface;
use Broker\Domain\Interfaces\System\InstanceInterface;

class ApplicationValidator implements EntityValidatorInterface
{
  /**
   * @var AbstractEntity
   */
  protected $entity;
  /**
   * @var array
   */
  protected $validationAttributes = [];
  /**
   * @var PartnerRepositoryInterface
   */
  protected $partnerRepository;
  /**
   * @var SchemaValidatorInterface
   */
  protected $schemaValidator;
  /**
   * @var InstanceInterface
   */
  protected $instance;

  /**
   * @param AbstractEntity $entity
   * @return $this
   * @codeCoverageIgnore
   */
  public function setEntity(AbstractEntity &$entity)
  {
    $this->entity = $entity;
    return $this;
  }

  /**
   * @return AbstractEntity
   * @codeCoverageIgnore
   */
  public function getEntity(): AbstractEntity
  {
    return $this->entity;
  }

  /**
   * @return PartnerRepositoryInterface
   * @codeCoverageIgnore
   */
  public function getPartnerRepository()
  {
    return $this->partnerRepository;
  }

  /**
   * @return array
   * @codeCoverageIgnore
   */
  public function getValidationAttributes(): array
  {
    return $this->validationAttributes;
  }

  /**
   * @param array $validationAttributes
   * @return ApplicationValidator
   * @codeCoverageIgnore
   */
  public function setValidationAttributes($validationAttributes)
  {
    $this->validationAttributes = $validationAttributes;
    return $this;
  }

  /**
   * @return SchemaValidatorInterface
   * @codeCoverageIgnore
   */
  public function getSchemaValidator()
  {
    return $this->schemaValidator;
  }

  /**
   * @return InstanceInterface
   * @codeCoverageIgnore
   */
  public function getInstance()
  {
    return $this->instance;
  }

  /**
   * @return \Broker\Domain\Interfaces\System\LoggerInterface
   */
  public function getLogger()
  {
    return $this->getInstance()->getLogger();
  }

  /**
   * ApplicationValidator constructor.
   * @param InstanceInterface $instance
   * @param PartnerRepositoryInterface $partnerRepository
   * @param SchemaValidatorInterface $schemaValidator
   */
  public function __construct(
    InstanceInterface $instance,
    PartnerRepositoryInterface $partnerRepository,
    SchemaValidatorInterface $schemaValidator
  )
  {
    $this->instance = $instance;
    $this->partnerRepository = $partnerRepository;
    $this->schemaValidator = $schemaValidator;
  }

  /**
   * @return bool
   */
  public function validate(): bool
  {
    foreach ($this->getPartners() as $partner)
    {
      if ($partner->hasDataMapper()) $this->validatePartnerRequirements($partner);
    }

    if (!empty($this->getValidationAttributes()))
    {
      $valid = $this->validateAttributes();
    }
    else
    {
      $valid = !$this->getEntity()->hasErrors();
    }

    if (!$valid) $this->getLogger()->info('Validation failed!', $this->getEntity()->getErrors());

    return $valid;
  }

  /**
   * @param Partner $partner
   * @return bool
   */
  protected function validatePartnerRequirements(Partner $partner)
  {
    $mapper = $partner->getDataMapper();
    $data = $mapper->mapAppToRequest($this->getEntity());
    $schema = json_encode($mapper->getRequestSchema());

    $validator = $this->getSchemaValidator();
    $validator->validate($data, $schema);

    if (!$validator->isValid())
    {
      $this->getEntity()->setErrors($mapper->mapErrorsToForm($validator->getErrors()));
      return false;
    }

    return true;
  }

  /**
   * @return array
   */
  public function getPartners()
  {
    return $this->getPartnerRepository()->getActivePartners();
  }

  public function validateAttributes()
  {
    $errors = array_intersect_key($this->getEntity()->getErrors(), array_flip($this->getValidationAttributes()));

    $this->getEntity()->clearErrors()->setErrors($errors);

    return empty($errors);
  }

  public function validateAttribute($attribute)
  {
    // TODO: Implement validateAttribute() method.
  }
}