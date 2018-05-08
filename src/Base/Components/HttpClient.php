<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 29.04.18
 * Time: 17:40
 */

namespace App\Base\Components;

use Broker\System\Log;

class HttpClient
{
  /**
   * @var string
   */
  protected $baseUrl;
  /**
   * @var mixed
   */
  protected $_client;
  /**
   * @var bool
   */
  protected $ok;
  /**
   * @var int
   */
  protected $statusCode;

  /**
   * @return string
   * @codeCoverageIgnore
   */
  public function getBaseUrl()
  {
    return $this->baseUrl;
  }

  /**
   * @param string $baseUrl
   * @return HttpClient
   * @codeCoverageIgnore
   */
  public function setBaseUrl(string $baseUrl)
  {
    $this->setClientOption(CURLOPT_URL, $baseUrl);
    $this->baseUrl = $baseUrl;
    return $this;
  }

  /**
   * @return mixed
   * @codeCoverageIgnore
   */
  private function getClient()
  {
    return $this->_client;
  }

  /**
   * @param mixed $client
   * @return HttpClient
   * @codeCoverageIgnore
   */
  private function setClient($client)
  {
    $this->_client = $client;
    return $this;
  }

  /**
   * @return bool
   * @codeCoverageIgnore
   */
  public function isOk()
  {
    return $this->ok;
  }

  /**
   * @param bool $ok
   * @return HttpClient
   * @codeCoverageIgnore
   */
  public function setOk(bool $ok)
  {
    $this->ok = $ok;
    return $this;
  }

  /**
   * @return int
   * @codeCoverageIgnore
   */
  public function getStatusCode()
  {
    return $this->statusCode;
  }

  /**
   * @param int $statusCode
   * @return HttpClient
   * @codeCoverageIgnore
   */
  public function setStatusCode(int $statusCode)
  {
    $this->statusCode = $statusCode;
    return $this;
  }

  /**
   * HttpClient constructor.
   */
  public function __construct()
  {
    $this->_client = curl_init();
  }

  /**
   * @return mixed
   * @throws \Exception
   */
  public function send()
  {
    $result = curl_exec($this->getClient());
    $this->setStatusCode($this->getResponseCode());

    if (curl_errno($this->getClient()))
    {
      Log::warning('Got error when making CURL request...', [curl_error($this->getClient())]);
    }

    curl_close($this->getClient());

    $this->setOk($this->getStatusCode() === 200);

    return $result;
  }

  /**
   * @param array $options
   */
  public function setClientOptions(array $options)
  {
    foreach ($options as $option => $value)
    {
      $this->setClientOption($option, $value);
    }
  }

  /**
   * @param string $option
   * @param $value
   */
  public function setClientOption(string $option, $value)
  {
    curl_setopt($this->getClient(), $option, $value);
  }

  /**
   * @param $headers
   */
  public function setClientHeaders($headers)
  {
    curl_setopt($this->getClient(), CURLOPT_HTTPHEADER, $headers);
  }

  /**
   * @return mixed
   */
  protected function getResponseCode()
  {
    return curl_getinfo($this->getClient(), CURLINFO_HTTP_CODE);
  }
}