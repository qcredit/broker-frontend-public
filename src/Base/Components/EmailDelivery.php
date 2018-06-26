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
use Broker\Domain\Interfaces\System\LoggerInterface;
use Broker\System\Error\InvalidConfigException;
use Monolog\Logger;
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
   * @var HttpClient
   */
  protected $client;
  /**
   * @var Container
   */
  protected $container;

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
   * @return HttpClient
   */
  public function getClient()
  {
    return $this->client;
  }

  /**
   * @param HttpClient $client
   * @return $this
   */
  public function setClient(HttpClient $client)
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
   * @return LoggerInterface
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getLogger()
  {
    return $this->getContainer()->get('BrokerInstance')->getLogger();
  }

  /**
   * EmailDelivery constructor.
   * @param Container $container
   */
  public function __construct(Container $container)
  {
    $this->container = $container;
    $this->client = new HttpClient();
  }

  /**
   * @param Message $message
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function send(Message $message)
  {
    $this->setMessage($message);

    try {
      $this->setupClient();
      $this->setupMessage();

      $result = $this->getClient()->send();
      $this->setOk(true);
    }
    catch (\Exception $ex)
    {
      $this->getLogger()->warning('Could not deliver e-mail!', [$this->getClient()->getError()]);
      $this->setOk(false);
    }
  }

  /**
   * @throws InvalidConfigException
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function setupMessage()
  {
    $settings = $this->getSettings();
    $message = $this->getMessage();

    $requestBody = [
      'personalizations' => [
        [
          'to' => [
            [
              'email' => $message->getRecipient()
            ]
          ],
          'subject' => $message->getTitle()
        ]
      ],
      'from' => [
        'email' => $settings['sender'],
        'name' => $settings['senderName']
      ],
      'content' => [
        [
          'type' => 'text/html',
          'value' => $message->getBody()
        ]
      ]
    ];

    $this->getClient()->setClientOption(CURLOPT_POSTFIELDS, json_encode($requestBody));
  }

  /**
   * @throws InvalidConfigException
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function setupClient()
  {
    $settings = $this->getSettings();

    $headers = [
      'Content-Type: application/json',
      sprintf('Authorization: Bearer %s', $settings['apiKey'])
    ];

    $this->getClient()->setClientHeaders($headers);
    $this->getClient()->setBaseUrl($settings['apiUrl']);
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
      $this->getLogger()->warning('Email client is not configured!');
      throw new InvalidConfigException('Email client is not configured!');
    }

    return $settings['mailer'];
  }

  public function getResponse()
  {
    // TODO: Implement getResponse() method.
  }
}
