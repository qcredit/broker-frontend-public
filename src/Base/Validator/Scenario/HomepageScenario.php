<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 12.06.18
 * Time: 17:20
 */

namespace App\Base\Validator\Scenario;

use App\Model\ApplicationForm;
use Broker\Domain\Interfaces\Partner\SchemaInterface;
use Broker\Domain\Interfaces\ValidationScenarioInterface;
use Slim\App;

class HomepageScenario implements ValidationScenarioInterface
{
  /**
   * @var SchemaInterface
   */
  protected $schema;
  /**
   * @var array
   */
  protected $rawData;

  /**
   * @param SchemaInterface $schema
   * @return $this
   * @codeCoverageIgnore
   */
  public function setSchema(SchemaInterface $schema)
  {
    $this->schema = $schema;
    return $this;
  }

  /**
   * @return SchemaInterface
   * @codeCoverageIgnore
   */
  public function getSchema()
  {
    return $this->schema;
  }

  /**
   * @return string
   */
  public function getJsonSchema()
  {
    return json_encode($this->schema);
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
   * @param array $data
   * @return $this
   * @codeCoverageIgnore
   */
  public function setRawData(array $data)
  {
    $this->rawData = $data;
    return $this;
  }

  public function applyScenario()
  {
    $originalSchema = $this->schema->getSchema();

    $this->schema = [
      'anyOf' => [
        [
          'type' => 'object',
          'required' => [
            ApplicationForm::ATTR_PHONE,
            ApplicationForm::ATTR_GDPR_1,
            ApplicationForm::ATTR_GDPR_2
          ],
          'properties' => [
            ApplicationForm::ATTR_PHONE => $originalSchema['properties'][ApplicationForm::ATTR_PHONE],
            ApplicationForm::ATTR_GDPR_1 => [
              'type' => 'number'
            ],
            ApplicationForm::ATTR_GDPR_2 => [
              'type' => 'number'
            ]
          ]
        ],
        [
          'type' => 'object',
          'required' => [
            ApplicationForm::ATTR_EMAIL,
            ApplicationForm::ATTR_GDPR_1,
            ApplicationForm::ATTR_GDPR_2
          ],
          'properties' => [
            ApplicationForm::ATTR_EMAIL => $originalSchema['properties'][ApplicationForm::ATTR_EMAIL],
            ApplicationForm::ATTR_GDPR_1 => [
              'type' => 'number'
            ],
            ApplicationForm::ATTR_GDPR_2 => [
              'type' => 'number'
            ]
          ]
        ]
      ]
    ];

    return $this;
  }
}