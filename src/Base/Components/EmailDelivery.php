<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 18.04.18
 * Time: 13:16
 */

namespace App\Base\Components;

use Broker\Domain\Entity\Message;
use Broker\Domain\Interfaces\MessageDeliveryInterface;

class EmailDelivery implements MessageDeliveryInterface
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