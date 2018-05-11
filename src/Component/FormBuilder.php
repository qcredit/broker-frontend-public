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
  const SECTION_GENERAL = 'general';
  const SECTION_INCOME = 'income';
  const SECTION_PERSONAL = 'personal';
  const SECTION_ADDITIONAL = 'additional';
  const SECTION_HOUSING = 'housing';
  const SECTION_ACCOUNT = 'account';

  const DEFAULT_ORDER = 100;
  const SECTION_ORDER = [
    self::SECTION_GENERAL,
    self::SECTION_PERSONAL,
    self::SECTION_HOUSING,
    self::SECTION_INCOME,
    self::SECTION_ACCOUNT,
    self::SECTION_ADDITIONAL
  ];

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
   * @var array
   */
  protected $requiredFields = [];
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
   * @return array
   * @codeCoverageIgnore
   */
  public function getRequiredFields()
  {
    return $this->requiredFields;
  }

  /**
   * @param array $requiredFields
   * @return FormBuilder
   * @codeCoverageIgnore
   */
  public function setRequiredFields(array $requiredFields)
  {
    $this->requiredFields = $requiredFields;
    return $this;
  }

  /**
   * @param string $fieldName
   * @return bool
   */
  public function isFieldRequired(string $fieldName): bool
  {
    return in_array($fieldName, $this->getRequiredFields());
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
   * @return array
   */
  public function getSectionOrder()
  {
    return self::SECTION_ORDER;
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
    $this->extractMergedSchemasFields();

    $this->addFieldToSection(self::SECTION_GENERAL, [
      'name' => 'marketingConsent',
      'type' => 'checkbox',
      'section' => self::SECTION_GENERAL,
      'label' => $this->getApplicationForm()->getFieldLabel('marketingConsent'),
      'order' => 4
    ]);

    $this->sortFields();
    $this->sortSections();

    return $this->getFields();
  }

  protected function extractMergedSchemasFields()
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
    if (isset($set['required']) && !empty($set['required']))
    {
      $this->setRequiredFields(array_unique(array_merge($this->getRequiredFields(), $set['required'])));
    }

    foreach ($set['properties'] as $fieldName => $field)
    {
      if ($this->hasField($fieldName)) continue;

      $this->addFieldToSection($field['section'] ?? self::SECTION_GENERAL, [
        'name' => $fieldName,
        'type' => $this->determineFieldType($field),
        'required' => $this->isFieldRequired($fieldName),
        'section' => $field['section'] ?? self::SECTION_GENERAL,
        'enum' => $field['enum'] ?? false,
        'label' => $this->getApplicationForm()->getFieldLabel($fieldName),
        'order' => $field['order'] ?? self::DEFAULT_ORDER
      ]);
    }
  }

  protected function sortFields()
  {
    $sections = $this->getFields();

    foreach ($sections as $section => &$set)
    {
      usort($set, function($a, $b) {
        return $a['order'] <=> $b['order'];
      });
    }

    $this->setFields($sections);
  }

  protected function sortSections()
  {
    $sections = $this->getFields();

    uksort($sections, function($a, $b) {
      return array_search($a, $this->getSectionOrder()) <=> array_search($b, $this->getSectionOrder());
    });

    $this->setFields($sections);
  }
}
