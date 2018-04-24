<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 24.04.18
 * Time: 10:36
 */

namespace App\Cron;

use Broker\Domain\Interfaces\Repository\ApplicationRepositoryInterface;
use Broker\Domain\Interfaces\Service\MessageDeliveryServiceInterface;

class SendChooseOffer implements BaseJob
{
  /**
   * @var MessageDeliveryServiceInterface
   */
  protected $messageDeliveryService;
  /**
   * @var ApplicationRepositoryInterface
   */
  protected $applicationRepository;

  /**
   * SendChooseOffer constructor.
   * @param MessageDeliveryServiceInterface $messageDeliveryService
   * @param ApplicationRepositoryInterface $applicationRepository
   */
  public function __construct(MessageDeliveryServiceInterface $messageDeliveryService, ApplicationRepositoryInterface $applicationRepository)
  {
    $this->messageDeliveryService = $messageDeliveryService;
    $this->applicationRepository = $applicationRepository;
  }

  /**
   * @return MessageDeliveryServiceInterface
   */
  public function getMessageDeliveryService()
  {
    return $this->messageDeliveryService;
  }

  /**
   * @param MessageDeliveryServiceInterface $messageDeliveryService
   * @return SendChooseOffer
   */
  public function setMessageDeliveryService(MessageDeliveryServiceInterface $messageDeliveryService)
  {
    $this->messageDeliveryService = $messageDeliveryService;
    return $this;
  }

  /**
   * @return ApplicationRepositoryInterface
   */
  public function getApplicationRepository()
  {
    return $this->applicationRepository;
  }

  /**
   * @param ApplicationRepositoryInterface $applicationRepository
   * @return SendChooseOffer
   */
  public function setApplicationRepository(ApplicationRepositoryInterface $applicationRepository)
  {
    $this->applicationRepository = $applicationRepository;
    return $this;
  }

  public function run(): bool
  {
    //$app = $this->getApplicationRepository()->getOneBy(['id' => 1]);
    print_r($this->getApplicationRepository()->getByJson('pin', '92090700966'));
    return true;
  }
}