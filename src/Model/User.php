<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 10.04.18
 * Time: 17:36
 */

namespace App\Model;

use App\Base\Interfaces\UserIdentityInterface;
use Broker\Domain\Entity\AbstractEntity;
use Broker\Domain\Entity\FluidDataTrait;

class User extends AbstractEntity implements UserIdentityInterface
{
  use FluidDataTrait;

  const ACCESS_LVL_ADMIN = 1;
  /**
   * @var integer
   */
  protected $id;
  /**
   * @var string
   */
  protected $email;
  /**
   * @var integer
   */
  protected $accessLevel;
  /**
   * @var string
   */
  protected $authKey;
  /**
   * @var \DateTime
   */
  protected $createdAt;

  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param int $id
   * @return User
   */
  public function setId($id)
  {
    $this->id = $id;
    return $this;
  }

  /**
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @param string $email
   * @return User
   */
  public function setEmail($email)
  {
    $this->email = $email;
    return $this;
  }

  /**
   * @return int
   */
  public function getAccessLevel()
  {
    return $this->accessLevel;
  }

  /**
   * @param int $accessLevel
   * @return User
   */
  public function setAccessLevel($accessLevel)
  {
    $this->accessLevel = $accessLevel;
    return $this;
  }

  /**
   * @return \DateTime
   */
  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  /**
   * @param \DateTime $createdAt
   * @return User
   */
  public function setCreatedAt(\DateTime $createdAt)
  {
    $this->createdAt = $createdAt;
    return $this;
  }

  public function getRole()
  {
    // TODO: Implement getRole() method.
  }

  /**
   * @return string
   */
  public function getAuthKey()
  {
    return $this->authKey;
  }

  /**
   * @return $this
   */
  public function generateAuthKey()
  {
    $this->authKey = bin2hex(openssl_random_pseudo_bytes(16));
    return $this;
  }

  public function isGuest(): bool
  {
    // TODO: Implement isGuest() method.
  }

  public function validateAuthKey(string $authKey): bool
  {
    return $authKey === $this->authKey;
  }
}