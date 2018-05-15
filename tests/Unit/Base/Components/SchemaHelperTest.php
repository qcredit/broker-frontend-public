<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 04.05.18
 * Time: 14:56
 */

namespace Tests\Unit\Base\Components;

use App\Base\Components\SchemaHelper;
use App\Base\Repository\PartnerDataMapperRepository;
use Broker\Domain\Interfaces\PartnerDataMapperInterface;
use Broker\System\BaseTest;

class SchemaHelperTest extends BaseTest
{
  public function testFlattenSchemaWithEmptySchema()
  {
    $schema = [
      'properties' => []
    ];

    $mock = $this->getMockBuilder(SchemaHelper::class)
      ->setMethods(['flattenSchemaProperties', 'flattenSchemaRequiredProperties'])
      ->getMock();
    $mock->expects($this->once())
      ->method('flattenSchemaProperties')
      ->willReturn([]);
    $mock->expects($this->once())
      ->method('flattenSchemaRequiredProperties')
      ->willReturn([]);

    $mock->flattenSchema($schema);
  }

  public function testFlattenSchemaWithRequiredFields()
  {
    $helper = new SchemaHelper();
    $schema = [
      'properties' => [],
      'required' => [
        'field1',
        'field2',
        'field3'
      ]
    ];

    $this->assertArraySubset($schema['required'], $helper->flattenSchema($schema)['required']);
  }

  public function testFlattenSchemaProperties()
  {
    $helper = new SchemaHelper();
    $properties = [
      'pin' => [
        'type' => 'string',
        'minLength' => 10
      ],
      'email' => [
        'type' => 'string',
        'minLength' => 2
      ]
    ];

    $this->assertSame($properties, $helper->flattenSchemaProperties($properties));
  }

  public function testFlattenSchemaPropertiesWithObject()
  {
    $helper = new SchemaHelper();
    $properties = [
      'pin' => [
        'type' => 'string',
        'minLength' => 10
      ],
      'income' => [
        'type' => 'object',
        'properties' => [
          'netPerMonth' => [
            'type' => 'number'
          ]
        ]
      ]
    ];

    $result = $helper->flattenSchemaProperties($properties);
    $this->assertArrayHasKey('netPerMonth', $result);
  }

  public function testFlattenSchemaPropertiesWithReference()
  {
    $mock = $this->getMockBuilder(SchemaHelper::class)
      ->setMethods(['getReferencedElement'])
      ->getMock();
    $properties = [
      'pin' => [
        'type' => 'string',
        'minLength' => 10
      ],
      'contactAddress' => [
        '$ref' => '#/definitions/addressObject'
      ]
    ];

    $mock->expects($this->once())
      ->method('getReferencedElement')
      ->willReturn([]);

    $result = $mock->flattenSchemaProperties($properties);
  }

  public function testFlattenSchemaRequiredProperties()
  {
    $required = [
      'property0',
      'property1',
      'property2',
      'property3'
    ];

    $requiredElementsToRemove = [
      'property1',
      'property2'
    ];

    $requiredElementsToAdd = [
      'boomshakalaka'
    ];

    $mock = $this->getMockBuilder(SchemaHelper::class)
      ->setMethods(['getRequiredElementsToRemove', 'getRequiredElementsToAdd'])
      ->getMock();
    $mock->expects($this->once())
      ->method('getRequiredElementsToRemove')
      ->willReturn($requiredElementsToRemove);
    $mock->expects($this->once())
      ->method('getRequiredElementsToAdd')
      ->willReturn($requiredElementsToAdd);

    $result = $mock->flattenSchemaRequiredProperties($required);
    $this->assertSame(array_merge(array_diff($required, $requiredElementsToRemove), $requiredElementsToAdd), $result);
  }

  public function testGetReferencedElement()
  {
    $reference = '#/definitions/someDefinition';

    $mock = $this->getMockBuilder(SchemaHelper::class)
      ->setMethods(['getDefinition'])
      ->getMock();
    $mock->expects($this->once())
      ->method('getDefinition')
      ->willReturn([]);

    $mock->getReferencedElement($reference);
  }

  public function testGetReferenceWithInvalidReference()
  {
    $reference = 'file://definitions/someDefinition';

    $mock = $this->getMockBuilder(SchemaHelper::class)
      ->setMethods(['getDefinition'])
      ->getMock();
    $mock->expects($this->never())
      ->method('getDefinition')
      ->willReturn([]);

    $mock->getReferencedElement($reference);
  }

  public function testGetDefinition()
  {
    $definition = 'oooooolaaaa';
    $schema = [
      'definitions' => [
        $definition => [
          'type' => 'object',
          'properties' => []
        ]
      ]
    ];
    $instance = new SchemaHelper();
    $instance->setCurrentSchema($schema);

    $this->assertSame($schema['definitions'][$definition], $instance->getDefinition($definition));
  }

  public function testGetNonExistingDefinition()
  {
    $definition = 'oooooolaaaa';
    $schema = [
      'definitions' => [
        'emalendur' => [
          'type' => 'object',
          'properties' => []
        ]
      ]
    ];
    $instance = new SchemaHelper();
    $instance->setCurrentSchema($schema);

    $this->assertSame([], $instance->getDefinition($definition));
  }

  public function testDefinitionHasElement()
  {
    $definitionName = 'addressObject';
    $element = 'street';
    $definition = [
      'street' => [

      ]
    ];

    $mock = $this->getMockBuilder(SchemaHelper::class)
      ->setMethods(['getDefinition'])
      ->getMock();
    $mock->expects($this->once())
      ->method('getDefinition')
      ->willReturn($definition);

    $this->assertTrue($mock->definitionHasElement($definitionName, $element));
  }

  public function testDefinitionHasntElement()
  {
    $definitionName = 'addressObject';
    $element = 'house';
    $definition = [
      'street' => [

      ]
    ];

    $mock = $this->getMockBuilder(SchemaHelper::class)
      ->setMethods(['getDefinition'])
      ->getMock();
    $mock->expects($this->once())
      ->method('getDefinition')
      ->willReturn($definition);

    $this->assertFalse($mock->definitionHasElement($definitionName, $element));
  }

  public function testCheckForRequiredElementsToAdd()
  {
    $subject = [
      'required' => [
        'one',
        'two',
        'three'
      ]
    ];

    $mock = $this->getMockBuilder(SchemaHelper::class)
      ->setMethods(['addRequiredElementToAdd'])
      ->getMock();

    $mock->expects($this->exactly(count($subject['required'])))
      ->method('addRequiredElementToAdd');

    $mock->checkForRequiredElementsToAdd($subject);
  }

  public function testMapPartnerSchemaToForm()
  {
    $requestPayload = [
      'field' => 'field',
      'anotherField' => 'actualField'
    ];
    $schema = [
      'properties' => [
        'firstName' => [
          'type' => 'string',
          'minLength' => 10
        ],
        'anotherField' => [
          'type' => 'string'
        ]
      ],
      'required' => [
        'firstName',
        'field',
        'anotherField'
      ]
    ];

    $mock = $this->getMockBuilder(SchemaHelper::class)->getMock();

    $result = $this->invokeMethod($mock, 'mapPartnerSchemaToForm', [$requestPayload, $schema]);
    $this->assertArrayHasKey('actualField', $result['properties']);
    $this->assertContains('actualField', $result['required']);
    $this->assertContains('firstName', $result['required']);
    $this->assertContains('field', $result['required']);
  }

  public function testMergePartnerSchemas()
  {
    $dataMapper = $this->getMockBuilder(PartnerDataMapperInterface::class)
      ->setMethods(['getDecodedConfigFile', 'getRequestPayload'])
      ->getMockForAbstractClass();
    $dataMapper->method('getDecodedConfigFile')
      ->willReturn(['requestSchema' => []]);
    $dataMappers = [
      $dataMapper
    ];

    $mock = $this->getMockBuilder(SchemaHelper::class)
      ->setMethods(['flattenSchema', 'mapPartnerSchemaToForm'])
      ->getMock();

    $mock->expects($this->once())
      ->method('flattenSchema')
      ->willReturnArgument(0);
    $mock->expects($this->once())
      ->method('mapPartnerSchemaToForm')
      ->willReturn([]);

    $mock->mergePartnersSchemas($dataMappers);
  }
}
