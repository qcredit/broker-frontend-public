<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 07.05.18
 * Time: 13:56
 */

namespace Tests\Unit\Component;

use App\Component\SchemaHelper;
use App\Base\Persistence\Doctrine\PartnerRepository;
use App\Base\Repository\PartnerDataMapperRepository;
use App\Component\FormBuilder;
use App\Model\ApplicationForm;
use Broker\Domain\Interfaces\PartnerDataMapperInterface;
use Broker\Domain\Interfaces\Repository\PartnerDataMapperRepositoryInterface;
use Broker\Domain\Interfaces\Repository\PartnerRepositoryInterface;
use Broker\System\BaseTest;
use Broker\Domain\Entity\Partner;

class FormBuilderTest extends BaseTest
{
  protected $partnerRepositoryMock;
  protected $dataMapperRepoMock;
  protected $mock;
  protected $dataMapperMock;

  public function setUp()
  {
    $this->partnerRepositoryMock = $this->getMockBuilder(PartnerRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getActivePartners'])
      ->getMock();

    $this->dataMapperRepoMock = $this->getMockBuilder(PartnerDataMapperRepository::class)
      ->disableOriginalConstructor()
      ->setMethods(['getDataMapperByPartnerId'])
      ->getMock();

    $this->dataMapperMock = $this->getMockBuilder(PartnerDataMapperInterface::class)
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();

    $this->mock = $this->getMockBuilder(FormBuilder::class)
      ->disableOriginalConstructor()
      ->setMethods(['getSectionOrder'])
      ->getMock();
  }

  public function test__construct()
  {
    $partnerRepositoryMock = $this->getMockBuilder(PartnerRepositoryInterface::class)
      ->getMockForAbstractClass();
    $instance = new FormBuilder($partnerRepositoryMock, new SchemaHelper());

    $this->assertInstanceOf(PartnerRepositoryInterface::class, $instance->getPartnerRepository());
  }

  public function testGetMergedPartnerSchemas()
  {
    $partners = [
      (new Partner())->setIdentifier('kala')->setDataMapper($this->dataMapperMock),
      (new Partner())->setIdentifier('supp')->setDataMapper($this->dataMapperMock)
    ];
    $this->partnerRepositoryMock->expects($this->once())
      ->method('getActivePartners')
      ->willReturn($partners);
    $schemaHelperMock = $this->getMockBuilder(SchemaHelper::class)
      ->setMethods(['mergePartnersSchemas'])
      ->getMock();
    $schemaHelperMock->method('mergePartnersSchemas')
      ->willReturnArgument(0);

    $this->mock->setSchemaHelper($schemaHelperMock);
    $this->mock->setPartnerRepository($this->partnerRepositoryMock);

    $result = $this->invokeMethod($this->mock, 'getMergedPartnerSchemas', []);
    $this->assertInstanceOf(PartnerDataMapperInterface::class, $result[0]);
  }

  public function testExtractMergedSchemasFields()
  {
    $mergedSchemas = [
      'allOf' => [
        [
          'properties' => [
            'netPerMonth' => [
              'type' => 'number'
            ]
          ]
        ],
        [
          'properties' => [
            'firstName' => [
              'type' => 'string'
            ]
          ]
        ]
      ]
    ];
    $mock = $this->getMockBuilder(FormBuilder::class)
      ->disableOriginalConstructor()
      ->setMethods(['getMergedPartnerSchemas', 'exploreSchema'])
      ->getMock();

    $mock->expects($this->once())
      ->method('getMergedPartnerSchemas')
      ->willReturn($mergedSchemas);
    $mock->expects($this->exactly(count($mergedSchemas['allOf'])))
      ->method('exploreSchema');

    $this->invokeMethod($mock, 'extractMergedSchemasFields', []);
  }

  public function testExploreSchema()
  {
    $schema = [
      'properties' => [
        'netPerMonth' => [
          'type' => 'number'
        ],
        'firstName' => [
          'type' => 'string',
          'section' => 'general'
        ]
      ]
    ];
    $mock = $this->getMockBuilder(FormBuilder::class)
      ->disableOriginalConstructor()
      ->setMethods(['getMergedPartnerSchemas', 'getFormFieldParameters'])
      ->getMock();

    $mock->setApplicationForm(new ApplicationForm());

    $mock->method('getFormFieldParameters')
      ->willReturn([]);

    $this->invokeMethod($mock, 'exploreSchema', [$schema]);
    $result = $mock->getFields();

    $this->assertArraySubset([0 => ['name' => 'netPerMonth', 'type' => 'text', 'section' => 'general']], $result);
    $this->assertArraySubset([1 => ['name' => 'firstName', 'type' => 'text', 'section' => 'general']], $result);
  }

  public function testExtractSchemaFieldsWithRequiredFields()
  {
    $schema = [
      'required' => [
        'netPerMonth'
      ],
      'properties' => [
        'netPerMonth' => [
          'type' => 'number'
        ],
        'firstName' => [
          'type' => 'string',
          'section' => 'personal'
        ]
      ]
    ];
    $mock = $this->getMockBuilder(FormBuilder::class)
      ->disableOriginalConstructor()
      ->setMethods(['getMergedPartnerSchemas', 'getFormFieldParameters'])
      ->getMock();

    $mock->setApplicationForm(new ApplicationForm());

    $mock->method('getFormFieldParameters')
      ->willReturn([]);

    $this->invokeMethod($mock, 'exploreSchema', [$schema]);
    $result = $mock->getFields();

    $this->assertArraySubset([0 => ['name' => 'netPerMonth', 'type' => 'text', 'section' => 'general', 'required' => true]], $result);
  }

  public function testHasField()
  {
    $fields = [
      [
        'name' => 'firstName',
        'type' => 'string',
        'section' => 'personal'
      ]
    ];
    $this->mock->setFields($fields);

    $this->assertTrue($this->mock->hasField('firstName'));
  }

  public function testHasNotField()
  {
    $fields = [
      [
        'name' => 'firstName',
        'type' => 'string',
        'section' => 'personal'
      ]
    ];
    $this->mock->setFields($fields);

    $this->assertFalse($this->mock->hasField('lastName'));
  }

  public function testSortSections()
  {
    $order = [
      'fruits',
      'vegetables',
      'meat',
      'dairy'
    ];

    $sections = [
      'dairy' => [],
      'fruits' => [],
      'vegetables' => [],
      'meat' => []
    ];

    $this->mock->setFieldsInSections($sections);
    $this->mock->method('getSectionOrder')
      ->willReturn($order);

    $this->invokeMethod($this->mock, 'sortSections', []);

    $this->assertSame($order, array_keys($this->mock->getFieldsInSections()));
  }

  public function testSortFields()
  {
    $fields = [
      'section1' => [
        [
          'name' => 'fname',
          'type' => 'string',
          'order' => 3
        ],
        [
          'name' => 'lname',
          'type' => 'string',
          'order' => 1
        ],
        [
          'name' => 'email',
          'type' => 'string',
          'order' => 0
        ]
      ]
    ];

    $this->mock->setFieldsInSections($fields);
    $this->invokeMethod($this->mock, 'sortFields', []);
    $this->assertSame(['email', 'lname','fname'], array_column($this->mock->getFieldsInSections()['section1'], 'name'));
  }
}
