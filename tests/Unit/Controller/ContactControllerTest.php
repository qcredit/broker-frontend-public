<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 17.05.18
 * Time: 15:50
 */

namespace Tests\Unit\Controller;

use App\Controller\ContactController;
use App\Model\ContactForm;
use App\Model\Contact;
use Broker\System\BaseTest;
use Slim\Container;
use Tests\Helpers\ControllerTestTrait;

class ContactControllerTest extends BaseTest
{
  use ControllerTestTrait;

  private $formMock;
  private $mock;

  public function setUp()
  {
    $this->setupMocks();
    $this->formMock = $this->getMockBuilder(ContactForm::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->mock = $this->getMockBuilder(ContactController::class)
      ->disableOriginalConstructor()
      ->setMethods(['render', 'getContactForm', 'getParsedBody'])
      ->getMock();
  }

  public function test__construct()
  {
    $instance = new ContactController($this->containerMock, $this->formMock);

    $this->assertInstanceOf(Container::class, $instance->getContainer());
    $this->assertInstanceOf(ContactForm::class, $instance->getContactForm());
  }

  public function testIndexAction()
  {
    $this->formMock->method('getModel')
      ->willReturn(new Contact());
    $this->mock->method('getContactForm')
      ->willReturn($this->formMock);
    $this->mock->method('render')
      ->willReturnArgument(2);
    $this->requestMock->method('isPost')
      ->willReturn(false);

    $result = $this->mock->indexAction($this->requestMock, $this->responseMock);
    $this->assertInstanceOf(Contact::class, $result['contact']);
  }

  public function testIndexActionWithPost()
  {
    $data = [
      'name' => 'Priit Toobal',
      'email' => 'priit@toobal.ee',
      'message' => 'Täna sauna teeme?'
    ];

    $this->formMock->method('getModel')
      ->willReturn(new Contact());
    $this->formMock->method('validate')
      ->willReturn(true);
    $this->formMock->method('send')
      ->willReturn(true);
    $this->formMock->method('load')
      ->willReturn(true);
    $this->mock->method('getContactForm')
      ->willReturn($this->formMock);
    $this->mock->method('render')
      ->willReturnArgument(2);
    $this->requestMock->method('isPost')
      ->willReturn(true);
    $this->mock->method('getParsedBody')
      ->willReturn($data);

    $result = $this->mock->indexAction($this->requestMock, $this->responseMock);
    $this->assertInstanceOf(Contact::class, $result['contact']);
    $this->assertTrue($result['sent']);
  }

  public function testIndexActionWithFailedValidation()
  {
    $data = [
      'name' => 'Priit Toobal',
      'email' => 'priit@toobal.ee',
      'message' => 'Täna sauna teeme?'
    ];

    $this->formMock->method('getModel')
      ->willReturn(new Contact());
    $this->formMock->method('validate')
      ->willReturn(false);
    $this->formMock->method('send')
      ->willReturn(true);
    $this->formMock->method('load')
      ->willReturn(true);
    $this->mock->method('getContactForm')
      ->willReturn($this->formMock);
    $this->mock->method('render')
      ->willReturnArgument(2);
    $this->requestMock->method('isPost')
      ->willReturn(true);
    $this->mock->method('getParsedBody')
      ->willReturn($data);

    $result = $this->mock->indexAction($this->requestMock, $this->responseMock);
    $this->assertInstanceOf(Contact::class, $result['contact']);
    $this->assertFalse($result['sent']);
  }

  public function testIndexActionWithFailedLoad()
  {
    $data = [
      'name' => 'Priit Toobal',
      'email' => 'priit@toobal.ee',
      'message' => 'Täna sauna teeme?'
    ];

    $this->formMock->method('getModel')
      ->willReturn(new Contact());
    $this->formMock->method('validate')
      ->willReturn(true);
    $this->formMock->method('send')
      ->willReturn(true);
    $this->formMock->method('load')
      ->willReturn(false);
    $this->mock->method('getContactForm')
      ->willReturn($this->formMock);
    $this->mock->method('render')
      ->willReturnArgument(2);
    $this->requestMock->method('isPost')
      ->willReturn(true);
    $this->mock->method('getParsedBody')
      ->willReturn($data);

    $result = $this->mock->indexAction($this->requestMock, $this->responseMock);
    $this->assertInstanceOf(Contact::class, $result['contact']);
    $this->assertFalse($result['sent']);
  }

  public function testIndexActionWithFailedSend()
  {
    $data = [
      'name' => 'Priit Toobal',
      'email' => 'priit@toobal.ee',
      'message' => 'Täna sauna teeme?'
    ];

    $this->formMock->method('getModel')
      ->willReturn(new Contact());
    $this->formMock->method('validate')
      ->willReturn(true);
    $this->formMock->method('send')
      ->willReturn(false);
    $this->formMock->method('load')
      ->willReturn(true);
    $this->mock->method('getContactForm')
      ->willReturn($this->formMock);
    $this->mock->method('render')
      ->willReturnArgument(2);
    $this->requestMock->method('isPost')
      ->willReturn(true);
    $this->mock->method('getParsedBody')
      ->willReturn($data);

    $result = $this->mock->indexAction($this->requestMock, $this->responseMock);
    $this->assertInstanceOf(Contact::class, $result['contact']);
    $this->assertFalse($result['sent']);
  }
}
