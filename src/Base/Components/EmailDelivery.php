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
use Broker\System\Error\InvalidConfigException;
use Broker\System\Log;
use PHPMailer\PHPMailer\PHPMailer;
use Slim\Container;

class EmailDelivery implements MessageDeliveryInterface
{
  /**
   * @var bool
   */
  protected $ok;
  /**
   * @var Message
   */
  protected $message;
  /**
   * @var PHPMailer
   */
  protected $client;
  /**
   * @var Container
   */
  protected $container;

  /**
   * EmailDelivery constructor.
   * @param Container $container
   */
  public function __construct(Container $container)
  {
    $this->container = $container;
    $this->client = new PHPMailer(true);
  }

  /**
   * @param Message $message
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function send(Message $message)
  {
    try {
      $this->setMessage($message);
      $this->setupClient();
      $this->setupMessage();

      $this->getClient()->send();
      $this->setOk(true);
    }
    catch (\Exception $ex)
    {
      $this->getContainer()->get('logger')->error('Could not deliver e-mail!', [$this->getClient()->ErrorInfo]);
      $this->setOk(false);
    }
  }

  /**
   * @param Message $message
   * @return $this
   */
  public function setMessage(Message $message)
  {
    $this->message = $message;
    return $this;
  }

  /**
   * @return Message
   */
  public function getMessage(): Message
  {
    return $this->message;
  }

  /**
   * @return PHPMailer
   */
  public function getClient()
  {
    return $this->client;
  }

  /**
   * @param PHPMailer $client
   * @return EmailDelivery
   */
  public function setClient(PHPMailer $client)
  {
    $this->client = $client;
    return $this;
  }

  /**
   * @return bool
   */
  public function isOk(): bool
  {
    return $this->ok;
  }

  /**
   * @param bool $value
   * @return $this
   */
  public function setOk(bool $value)
  {
    $this->ok = $value;
    return $this;
  }

  /**
   * @return Container
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @param Container $container
   * @return EmailDelivery
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @throws InvalidConfigException
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function setupMessage()
  {
    $settings = $this->getSettings();
    $client = $this->getClient();
    $message = $this->getMessage();

    $client->isHTML(true);
    $client->Subject = $message->getTitle();
    $client->Body = $message->getBody();
    //$client->setFrom($settings['sender'], $settings['senderName']);
    $client->From = $settings['sender'];
    $client->FromName = $settings['senderName'];
    $client->addAddress($message->getRecipient());
  }

  /**
   * @throws InvalidConfigException
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function setupClient()
  {
    $settings = $this->getSettings();
    $client = $this->getClient();
    $client->isSMTP();
    $client->Host = $settings['host'];
    $client->SMTPAuth = true;
    $client->Username = $settings['username'];
    $client->Password = $settings['password'];
    $client->Port = $settings['port'];
    $client->SMTPSecure = $settings['secure'];
  }

  /**
   * @return mixed
   * @throws InvalidConfigException
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function getSettings()
  {
    $settings = $this->getContainer()->get('settings');

    if (!isset($settings['mailer']))
    {
      Log::warning('Email client is not configured!');
      throw new InvalidConfigException('Email client is not configured!');
    }

    return $settings['mailer'];
  }
}