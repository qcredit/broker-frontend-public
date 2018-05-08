<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 07.05.18
 * Time: 13:52
 */

namespace App\Component;

use App\Base\Components\SchemaHelper;
use App\Model\ApplicationForm;
use Broker\Domain\Interfaces\Repository\PartnerDataMapperRepositoryInterface;
use Broker\Domain\Interfaces\Repository\PartnerRepositoryInterface;

class FormBuilder
{
  /**
   * @var PartnerDataMapperRepositoryInterface
   */
  protected $partnerDataMapperRepository;
  /**
   * @var PartnerRepositoryInterface
   */
  protected $partnerRepository;
  /**
   * @var SchemaHelper
   */
  protected $schemaHelper;
  /**
   * @var array
   */
  protected $fields = [];
  /**
   * @var ApplicationForm
   */
  protected $applicationForm;

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
   * @return FormBuilder
   * @codeCoverageIgnore
   */
  public function setPartnerDataMapperRepository(PartnerDataMapperRepositoryInterface $partnerDataMapperRepository)
  {
    $this->partnerDataMapperRepository = $partnerDataMapperRepository;
    return $this;
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
   * @param PartnerRepositoryInterface $partnerRepository
   * @return FormBuilder
   * @codeCoverageIgnore
   */
  public function setPartnerRepository(PartnerRepositoryInterface $partnerRepository)
  {
    $this->partnerRepository = $partnerRepository;
    return $this;
  }

  /**
   * @return SchemaHelper
   * @codeCoverageIgnore
   */
  public function getSchemaHelper()
  {
    return $this->schemaHelper;
  }

  /**
   * @param SchemaHelper $schemaHelper
   * @return FormBuilder
   * @codeCoverageIgnore
   */
  public function setSchemaHelper($schemaHelper)
  {
    $this->schemaHelper = $schemaHelper;
    return $this;
  }

  /**
   * @return array
   * @codeCoverageIgnore
   */
  public function getFields()
  {
    return $this->fields;
  }

  /**
   * @param array $fields
   * @return FormBuilder
   * @codeCoverageIgnore
   */
  public function setFields($fields)
  {
    $this->fields = $fields;
    return $this;
  }

  /**
   * @param array $field
   * @return $this
   * @codeCoverageIgnore
   */
  public function addField(array $field)
  {
    $this->fields[] = $field;
    return $this;
  }

  /**
   * @param string $section
   * @param array $field
   * @return $this
   */
  public function addFieldToSection(string $section, array $field)
  {
    $this->fields[$section][] = $field;
    return $this;
  }

  /**
   * @param string $fieldName
   * @return bool
   */
  public function hasField(string $fieldName): bool
  {
    return array_search($fieldName, array_column($this->getFields(), 'name')) !== false;
  }

  /**
   * @return ApplicationForm
   * @codeCoverageIgnore
   */
  public function getApplicationForm()
  {
    return $this->applicationForm;
  }

  /**
   * @param ApplicationForm $applicationForm
   * @return FormBuilder
   * @codeCoverageIgnore
   */
  public function setApplicationForm(ApplicationForm $applicationForm)
  {
    $this->applicationForm = $applicationForm;
    return $this;
  }

  /**
   * FormBuilder constructor.
   * @param PartnerDataMapperRepositoryInterface $partnerDataMapperRepository
   * @param PartnerRepositoryInterface $partnerRepository
   * @param SchemaHelper $schemaHelper
   */
  public function __construct(
    PartnerDataMapperRepositoryInterface $partnerDataMapperRepository,
    PartnerRepositoryInterface $partnerRepository,
    SchemaHelper $schemaHelper
  )
  {
    $this->setPartnerDataMapperRepository($partnerDataMapperRepository);
    $this->setPartnerRepository($partnerRepository);
    $this->setSchemaHelper($schemaHelper);
    $this->applicationForm = new ApplicationForm();
  }

  /**
   * @return array
   */
  protected function getMergedPartnerSchemas()
  {
    $partners = $this->getPartnerRepository()->getActivePartners();

    $dataMappers = [];
    foreach ($partners as $partner)
    {
      $dataMappers[] = $this->getPartnerDataMapperRepository()->getDataMapperByPartnerId($partner->getIdentifier());
    }

    return $this->getSchemaHelper()->mergePartnersSchemas($dataMappers);
  }


  protected function searchSchemasForUniqueFields()
  {
    $mergedSchema = $this->getMergedPartnerSchemas();
    foreach ($mergedSchema['allOf'] as $set)
    {
      $set = json_decode(json_encode($set), true);
      $this->extractSchemaFields($set);
    }
  }

  /**
   * @param array $set
   */
  protected function extractSchemaFields(array $set)
  {
    foreach ($set['properties'] as $fieldName => $field)
    {
      if ($this->hasField($fieldName)) continue;

      $this->addFieldToSection($field['section'] ?? 'general', [
        'name' => $fieldName,
        'type' => $this->determineFieldType($field),
        'section' => $field['section'] ?? 'general',
        'enum' => $field['enum'] ?? false,
        'label' => $this->getApplicationForm()->getFieldLabel($fieldName),
        'order' => $field['priority'] ?? 100
      ]);
    }
  }

  /**
   * @param $field
   * @return string
   */
  protected function determineFieldType($field)
  {
    if (isset($field['enum'])) return 'select';

    if ($field['type'] == 'boolean') return 'checkbox';

    return 'text';
  }

  /**
   * @return array
   */
  public function getFormFields()
  {
    $this->searchSchemasForUniqueFields();

    $this->addFieldToSection('general', [
      'name' => 'marketingConsent',
      'type' => 'checkbox',
      'section' => 'general',
      'label' => $this->getApplicationForm()->getFieldLabel('marketingConsent')
    ]);

    $this->sortFields();

    return $this->getFields();
  }

  protected function sortFields()
  {
    $fields = $this->getFields();

    foreach ($fields as $section => &$set)
    {
      usort($set, function($a, $b) {
        return $a['order'] <=> $b['order'];
      });
    }

    $this->setFields($fields);
  }
}