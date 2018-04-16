<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 13.04.18
 * Time: 12:09
 */

namespace App\Base\Validator;

use Broker\Domain\Interfaces\SchemaValidatorInterface;
use JsonSchema\Validator;

class SchemaValidator implements SchemaValidatorInterface
{
  /**
   * @var Validator
   */
  protected $validator;

  /**
   * SchemaValidator constructor.
   */
  public function __construct()
  {
    $this->validator = new Validator();
  }

  /**
   * @return Validator
   */
  public function getValidator()
  {
    return $this->validator;
  }

  /**
   * @param Validator $validator
   * @return SchemaValidator
   */
  public function setValidator($validator)
  {
    $this->validator = $validator;
    return $this;
  }

  /**
   * @param string $data
   * @param string $schema
   */
  public function validate(string $data, string $schema)
  {
    $data = json_decode($data);
    $schema = json_decode($schema);

    $this->getValidator()->validate($data, $schema);
  }

  /**
   * @return bool
   */
  public function isValid(): bool
  {
    return $this->getValidator()->isValid();
  }

  /**
   * @return array
   */
  public function getErrors()
  {
    return $this->getValidator()->getErrors();
  }
}