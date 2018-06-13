<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 13.04.18
 * Time: 12:09
 */

namespace App\Base\Validator;

use Broker\Domain\Interfaces\SchemaValidatorInterface;
use JsonSchema\Constraints\Constraint;
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
   * @return int
   */
  public function validate(string $data, string $schema)
  {
    $data = json_decode($data);
    $schema = json_decode($schema);

    return $this->getValidator()->validate($data, $schema, Constraint::CHECK_MODE_COERCE_TYPES);
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
    return $this->getFormattedErrors();
  }

  /**
   * @return array
   */
  protected function getFormattedErrors()
  {
    $fatErrors = $this->getValidator()->getErrors();
    $errors = [];

    foreach ($fatErrors as $error)
    {
      if (isset($error['property']) && $error['property'] == '')
      {
        $errors['general'] = $this->beautifyErrorMessage($error['message']);
      }
      else if (isset($error['property']))
      {
        $parts = explode('.', $error['property']);
        if ($parts)
        {
          $errors[end($parts)] = $this->beautifyErrorMessage($error['message']);
        }
        else {
          $errors[$error['property']] = $this->beautifyErrorMessage($error['message']);
        }
      }
    }

    return $errors;
  }

  /**
   * @param $message
   * @return string
   */
  protected function beautifyErrorMessage($message)
  {
    if (strpos($message, 'Does not have a value in the enumeration') !== false)
    {
      return 'Please provide a value in provided range.';
    }

    if (strpos($message, 'Does not match the regex pattern') !== false)
    {
      return 'Invalid format provided.';
    }

    if (strpos($message, 'String value found, but a number is required') !== false)
    {
      return _('Please provide a number');
    }

    if (preg_match('/The property .*? is required/', $message))
    {
      return _('This field is required');
    }

    return $message;
  }
}