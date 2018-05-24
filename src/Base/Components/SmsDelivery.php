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
use Broker\System\Log;
use Monolog\Logger;
use Slim\Container;

class SmsDelivery implements MessageDeliveryInterface
{
  /**
   * @var HttpClient
   */
  protected $_client;
  /**
   * @var Container
   */
  protected $container;
  /**
   * @var Message
   */
  protected $message;
  /**
   * @var mixed
   */
  protected $response;
  /**
   * @var bool
   */
  protected $ok;
  /**
   * @return HttpClient
   * @codeCoverageIgnore
   */
  public function getClient()
  {
    return $this->_client;
  }

  /**
   * @param HttpClient $client
   * @return SmsDelivery
   * @codeCoverageIgnore
   */
  public function setClient(HttpClient $client)
  {
    $this->_client = $client;
    return $this;
  }

  /**
   * @return Container
   * @codeCoverageIgnore
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @param Container $container
   * @return SmsDelivery
   * @codeCoverageIgnore
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @return Logger
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getLogger()
  {
    return $this->getContainer()->get('logger');
  }

  /**
   * SmsDelivery constructor.
   * @param Container $container
   */
  public function __construct(Container $container)
  {
    $this->_client = new HttpClient();
    $this->container = $container;
  }

  /**
   * @param Message $message
   * @throws \Exception
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function send(Message $message)
  {
    $this->setMessage($message);
    $this->setup();

    $result = $this->getClient()->send();

    $this->handleResult($result);
  }

  /**
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function setup()
  {
    $settings = $this->getSettings();
    $url = $settings['apiUrl'] . '?' .
      http_build_query(
        [
          'username' => $settings['username'],
          'password' => $settings['password'],
          'from' => $settings['senderName'],
          'to' => $this->getMessage()->getRecipient(),
          'text' => $this->getMessage()->getBody()
        ]
      );

    $this->getClient()->setBaseUrl($url);
    $this->getClient()->setClientOption(CURLOPT_RETURNTRANSFER, true);
    $this->getClient()->setClientOption(CURLOPT_SSL_VERIFYPEER, false);
  }

  /**
   * @param $result
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function handleResult($result)
  {
    list($resp, $code) = explode(' ', $result);

    if ($resp != 'OK')
    {
      $this->getLogger()->warning($this->resolveErrorCode($code), [$result]);
      $this->setOk(false);
    }

    $this->setOk($this->getClient()->isOk() && true);
  }

  /**
   * @param $code
   * @return mixed|string
   */
  protected function resolveErrorCode($code)
  {
    $codes = [
      101 => 'Access restricted, wrong credentials. Check the username and password values!',
      102 => 'Parameters are wrong or missing. Check that all the required parameters are present.',
      103 => 'Invalid IP address. The IP address you made the request from, is not in the whitelist.',
      209 => 'Server failure, try again after a few seconds or try the api3.messente.com backup server.'
    ];

    if (!isset($codes[$code]))
    {
      return 'Unknown error!';
    }

    return $codes[$code];
  }

  /**
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function getSettings()
  {
    return $this->getContainer()->get('settings')['messente'];
  }

  /**
   * @param Message $message
   * @return $this
   * @codeCoverageIgnore
   */
  public function setMessage(Message $message)
  {
    $this->message = $message;
    return $this;
  }

  /**
   * @return Message
   * @codeCoverageIgnore
   */
  public function getMessage(): Message
  {
    return $this->message;
  }

  /**
   * @param bool $ok
   * @return $this
   * @codeCoverageIgnore
   */
  public function setOk(bool $ok)
  {
    $this->ok = $ok;
    return $this;
  }

  /**
   * @return bool
   * @codeCoverageIgnore
   */
  public function isOk(): bool
  {
    return $this->ok;
  }

  /**
   * @return mixed
   * @codeCoverageIgnore
   */
  public function getResponse()
  {
    return $this->response;
  }
}