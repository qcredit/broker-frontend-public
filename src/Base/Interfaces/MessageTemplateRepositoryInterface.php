<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 26.04.18
 * Time: 15:16
 */

namespace App\Base\Interfaces;

use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\Message;
use Broker\Domain\Entity\Offer;

interface MessageTemplateRepositoryInterface
{
  public function getTemplateByPath(string $path, array $arguments = []): string;
  public function getOfferLinkMessage(Application $application);
  public function getOfferConfirmationMessage(Offer $offer);
}