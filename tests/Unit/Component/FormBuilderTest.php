<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 07.05.18
 * Time: 13:56
 */

namespace Tests\Unit\Component;

use App\Base\Components\SchemaHelper;
use App\Base\Persistence\Doctrine\PartnerRepository;
use App\Base\Repository\PartnerDataMapperRepository;
use App\Component\FormBuilder;
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
      ->setMethods(null)
      ->getMock();
  }

  public function test__construct()
  {
    $dataMapperRepo = $this->getMockBuilder(PartnerDataMapperRepositoryInterface::class)
      ->getMockForAbstractClass();
    $partnerRepositoryMock = $this->getMockBuilder(PartnerRepositoryInterface::class)
      ->getMockForAbstractClass();
    $instance = new FormBuilder($dataMapperRepo, $partnerRepositoryMock, new SchemaHelper());

    $this->assertInstanceOf(PartnerDataMapperRepositoryInterface::class, $instance->getPartnerDataMapperRepository());
    $this->assertInstanceOf(PartnerRepositoryInterface::class, $instance->getPartnerRepository());
  }

  public function testGetMergedPartnerSchemas()
  {
    $partners = [
      (new Partner())->setIdentifier('kala'),
      (new Partner())->setIdentifier('supp')
    ];
    $this->partnerRepositoryMock->expects($this->once())
      ->method('getActivePartners')
      ->willReturn($partners);
    $this->dataMapperRepoMock->expects($this->exactly(count($partners)))
      ->method('getDataMapperByPartnerId')
      ->willReturn($this->dataMapperMock);
    $schemaHelperMock = $this->getMockBuilder(SchemaHelper::class)
      ->setMethods(['mergePartnersSchemas'])
      ->getMock();
    $schemaHelperMock->method('mergePartnersSchemas')
      ->willReturnArgument(0);

    $this->mock->setSchemaHelper($schemaHelperMock);
    $this->mock->setPartnerRepository($this->partnerRepositoryMock);
    $this->mock->setPartnerDataMapperRepository($this->dataMapperRepoMock);

    $result = $this->invokeMethod($this->mock, 'getMergedPartnerSchemas', []);
    $this->assertInstanceOf(PartnerDataMapperInterface::class, $result[0]);
  }

  public function testSearchSchemasForUniqueFields()
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
      ->setMethods(['getMergedPartnerSchemas', 'extractSchemaFields'])
      ->getMock();

    $mock->expects($this->once())
      ->method('getMergedPartnerSchemas')
      ->willReturn($mergedSchemas);
    $mock->expects($this->exactly(count($mergedSchemas['allOf'])))
      ->method('extractSchemaFields');

    $this->invokeMethod($mock, 'searchSchemasForUniqueFields', []);
  }

  public function testExtractSchemaFields()
  {
    $schema = [
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
      ->setMethods(['getMergedPartnerSchemas'])
      ->getMock();

    $this->invokeMethod($mock, 'extractSchemaFields', [$schema]);
    $result = $mock->getFields();

    $this->assertArraySubset(['general' => [0 => ['name' => 'netPerMonth', 'type' => 'text', 'section' => 'general']]], $result);
    $this->assertArraySubset(['personal' => [0 => ['name' => 'firstName', 'type' => 'text', 'section' => 'personal']]], $result);
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
}
