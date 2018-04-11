<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 10.04.18
 * Time: 17:36
 */

namespace App\Model;

use Broker\Domain\Entity\AbstractEntity;

class User extends AbstractEntity
{
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
}