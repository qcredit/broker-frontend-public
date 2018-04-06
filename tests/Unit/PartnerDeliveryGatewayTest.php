<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 03.04.18
 * Time: 13:32
 */

namespace Tests\Unit;

use App\Base\PartnerDeliveryGateway;
use Broker\Domain\Entity\Partner;
use Broker\Domain\Entity\PartnerRequest;
use PHPUnit\Framework\TestCase;

class PartnerDeliveryGatewayTest extends TestCase
{
  protected $gatewayMock;

  public function setUp()
  {
    $this->gatewayMock = $this->getMockBuilder(PartnerDeliveryGateway::class)
      ->setMethods(['sendApiRequest'])
      ->getMock();
  }

  public function testSend()
  {
    $request = new PartnerRequest();
    $request->setPartner((new Partner())->setUseApi(true));

    $mock = $this->gatewayMock;
    $mock->expects($this->once())
      ->method('sendApiRequest')
      ->willReturn(true);

    $mock->send($request);
  }

  public function testNotSendApi()
  {
    $request = new PartnerRequest();
    $request->setPartner((new Partner())->setUseApi(false));

    $mock = $this->gatewayMock;
    $mock->expects($this->never())
      ->method('sendApiRequest')
      ->willReturn(true);

    $mock->send($request);
  }
}
