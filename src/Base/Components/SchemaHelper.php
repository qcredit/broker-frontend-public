<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 04.05.18
 * Time: 14:55
 */

namespace App\Base\Components;

use App\Model\ApplicationForm;
use Broker\Domain\Interfaces\Repository\PartnerDataMapperRepositoryInterface;
use Broker\System\Helper;
use Broker\System\Log;

class SchemaHelper
{
  /**
   * @var array
   */
  protected $currentSchema;
  /**
   * @var array
   */
  protected $requiredElementsToRemove = [];
  /**
   * @var array
   */
  protected $requiredElementsToAdd = [];
  /**
   * @var PartnerDataMapperRepositoryInterface
   */
  protected $partnerDataMapperRepository;

  /**
   * @return array
   * @codeCoverageIgnore
   */
  public function getCurrentSchema()
  {
    return $this->currentSchema;
  }

  /**
   * @param array $currentSchema
   * @return SchemaHelper
   * @codeCoverageIgnore
   */
  public function setCurrentSchema(array $currentSchema)
  {
    $this->currentSchema = $currentSchema;
    return $this;
  }

  /**
   * @return array
   * @codeCoverageIgnore
   */
  public function getRequiredElementsToRemove()
  {
    return $this->requiredElementsToRemove;
  }

  /**
   * @param array $requiredElementsToRemove
   * @return SchemaHelper
   * @codeCoverageIgnore
   */
  public function setRequiredElementsToRemove($requiredElementsToRemove)
  {
    $this->requiredElementsToRemove = $requiredElementsToRemove;
    return $this;
  }

  /**
   * @param $requiredElementToRemove
   * @return $this
   * @codeCoverageIgnore
   */
  public function addRequiredElementToRemove($requiredElementToRemove)
  {
    $this->requiredElementsToRemove[] = $requiredElementToRemove;
    return $this;
  }

  /**
   * @return array
   * @codeCoverageIgnore
   */
  public function getRequiredElementsToAdd()
  {
    return $this->requiredElementsToAdd;
  }

  /**
   * @param array $requiredElementsToAdd
   * @return SchemaHelper
   * @codeCoverageIgnore
   */
  public function setRequiredElementsToAdd($requiredElementsToAdd)
  {
    $this->requiredElementsToAdd = $requiredElementsToAdd;
    return $this;
  }

  /**
   * @param $requiredElementToAdd
   * @return $this
   * @codeCoverageIgnore
   */
  public function addRequiredElementToAdd($requiredElementToAdd)
  {
    $this->requiredElementsToAdd[] = $requiredElementToAdd;
    return $this;
  }

  /**
   * @return PartnerDataMapperRepositoryInterface
   * @codeCoverageIgnore
   */
  public function getPartnerDataMapperRepository()
  {
    return $this->partnerDataMapperRepository;
  }

  /**
   * @param PartnerDataMapperRepositoryInterface $partnerDataMapperRepository
   * @return SchemaHelper
   * @codeCoverageIgnore
   */
  public function setPartnerDataMapperRepository(PartnerDataMapperRepositoryInterface $partnerDataMapperRepository)
  {
    $this->partnerDataMapperRepository = $partnerDataMapperRepository;
    return $this;
  }

  /**
   * @param array $schema
   * @return array
   */
  public function flattenSchema(array $schema)
  {
    $this->setCurrentSchema($schema);

    $result = [
      'type' => 'object',
      'required' => [],
      'properties' => [],
      'definitions' => []
    ];

    if (isset($schema['required']))
    {
      $result['required'] = $schema['required'];
    }

    $result['properties'] = $this->flattenSchemaProperties($schema['properties']);
    $result['required'] = array_values(array_unique($this->flattenSchemaRequiredProperties($schema['required'])));

    return $result;
  }

  /**
   * @param array $properties
   * @return array
   */
  public function flattenSchemaProperties(array $properties)
  {
    $result = [];

    foreach ($properties as $key => $property)
    {
      if ($property['type'] === 'object')
      {
        $this->addRequiredElementToRemove($key);
        $this->checkForRequiredElementsToAdd($property);
        $result = array_merge($result, $this->flattenSchemaProperties($property['properties']));
      }
      else if (array_key_exists('$ref', $property))
      {
        $this->flattenReference($key, $property, $result);
      }
      else
      {
        $result[$key] = $property;
      }
    }

    return $result;
  }

  /**
   * @param $requiredProperties
   * @return array
   */
  public function flattenSchemaRequiredProperties($requiredProperties)
  {
    foreach ($this->getRequiredElementsToRemove() as $element)
    {
      if (in_array($element, $requiredProperties)) unset($requiredProperties[array_search($element, $requiredProperties)]);
    }

    foreach ($this->getRequiredElementsToAdd() as $element)
    {
      $requiredProperties[] = $element;
    }

    return array_values($requiredProperties);
  }

  /**
   * @param string $reference
   * @return array|mixed
   */
  public function getReferencedElement(string $reference)
  {
    if (preg_match('/#\/definitions\/(.*)/', $reference, $matches))
    {
      return $this->getDefinition($matches[1]);
    }

    return [];
  }

  /**
   * @param string $definitionName
   * @param string $element
   * @return bool
   */
  public function definitionHasElement(string $definitionName, string $element)
  {
    $definition = $this->getDefinition($definitionName);
    return isset($definition[$element]);
  }

  /**
   * @param $definition
   * @return mixed
   */
  public function getDefinition($definition)
  {
    $schema = $this->getCurrentSchema();

    return $schema['definitions'][$definition] ?? [];
  }

  /**
   * @param array $property
   */
  public function checkForRequiredElementsToAdd(array $property)
  {
    if (isset($property['required']))
    {
      foreach ($property['required'] as $element)
      {
        $this->addRequiredElementToAdd($element);
      }
    }
  }

  /**
   * @param $key
   * @param $property
   * @param $result
   */
  protected function flattenReference($key, $property, &$result): void
  {
    $this->addRequiredElementToRemove($key);

    $referencedElement = $this->getReferencedElement($property['$ref']);

    $this->checkForRequiredElementsToAdd($referencedElement);

    if (isset($referencedElement['enum']))
    {
      $result[$key] = $referencedElement;
    }
    else
    {
      if (isset($referencedElement['properties']))
      {
        $result = array_merge($result, $this->flattenSchemaProperties($referencedElement['properties']));
      }
    }
  }

  /**
   * @param array $partnersDataMappers
   * @return array
   * @throws \Exception
   */
  public function mergePartnersSchemas(array $partnersDataMappers)
  {
    $combined = [
      'allOf' => [],
      'definitions' => []
    ];

    foreach ($partnersDataMappers as $dataMapper)
    {
      $schema = $dataMapper->getDecodedConfigFile()['requestSchema'];
      $schema = $this->flattenSchema($schema);
      $schema = $this->mapPartnerSchemaToForm($dataMapper->getRequestPayload(), $schema);

      if (isset($schema['definitions']))
      {
        $combined['definitions'] = Helper::mergeArraysRecursively($combined['definitions'], $schema['definitions']);
        unset($schema['definitions']);
      }

      $schema = $this->substituteEnumFieldValues($schema);

      $combined['allOf'][] = json_decode(json_encode($schema));
    }

    if (empty($combined['definitions'])) unset($combined['definitions']);

    return $combined;
  }

  /**
   * @param $requestPayload
   * @param $schema
   * @return mixed
   */
  protected function mapPartnerSchemaToForm($requestPayload, $schema)
  {
    $requestPayload = Helper::flattenArray($requestPayload);
    array_walk($requestPayload, function($value, $key) use ($requestPayload, &$schema) {
      if (array_key_exists($key, $schema['properties']) && $key !== $value)
      {
        $schema['properties'][$value] = $schema['properties'][$key];
        unset($schema['properties'][$key]);
      }
      if (($found = array_search($key, $schema['required'])) !== false)
      {
        unset($schema['required'][$found]);
        $schema['required'][] = $value;
      }
    });

    $schema['required'] = array_values($schema['required']);

    return $schema;
  }

  /**
   * @param $schema
   * @return mixed
   * @throws \Exception
   */
  protected function substituteEnumFieldValues($schema)
  {
    foreach ($schema['properties'] as $key => &$property)
    {
      if (!isset($property['enum'])) continue;

      $formEnums = ApplicationForm::getEnumFields();
      if (isset($formEnums[$key]))
      {
        $property['enum'] = array_keys($formEnums[$key]);
      }
      else
      {
        Log::warning(sprintf('Cannot get ENUM values for key %s', $key));
      }
    }

    return $schema;
  }
}