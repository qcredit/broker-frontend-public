<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 17.05.18
 * Time: 13:22
 */

namespace App\Model;

use Broker\Domain\Entity\AbstractEntity;
use Broker\Domain\Entity\FluidDataTrait;

class Contact extends AbstractEntity
{
  use FluidDataTrait;
  /**
   * @var string
   */
  private $name;
  /**
   * @var string
   */
  private $email;
  /**
   * @var string
   */
  private $message;

  /**
   * @return string
   * @codeCoverageIgnore
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param string $name
   * @return Contact
   * @codeCoverageIgnore
   */
  public function setName(string $name)
  {
    $this->name = $name;
    return $this;
  }

  /**
   * @return string
   * @codeCoverageIgnore
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @param string $email
   * @return Contact
   * @codeCoverageIgnore
   */
  public function setEmail(string $email)
  {
    $this->email = $email;
    return $this;
  }

  /**
   * @return string
   * @codeCoverageIgnore
   */
  public function getMessage()
  {
    return $this->message;
  }

  /**
   * @param string $message
   * @return Contact
   * @codeCoverageIgnore
   */
  public function setMessage(string $message)
  {
    $this->message = $message;
    return $this;
  }
}