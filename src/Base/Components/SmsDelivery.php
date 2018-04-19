<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 18.04.18
 * Time: 13:17
 */

namespace App\Base\Components;

use Broker\Domain\Entity\Message;
use Broker\Domain\Interfaces\MessageDeliveryInterface;

class SmsDelivery implements MessageDeliveryInterface
{

  public function send(Message $message)
  {
    // TODO: Implement send() method.
  }

  public function setMessage(Message $message)
  {
    // TODO: Implement setMessage() method.
  }

  public function getMessage(): Message
  {
    // TODO: Implement getMessage() method.
  }

  public function isOk(): bool
  {
    // TODO: Implement isOk() method.
  }
}