<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 05.06.18
 * Time: 10:15
 */

namespace App\Base\Event;

use App\Model\ApplicationForm;
use Broker\Domain\Interfaces\System\Event\EventListenerInterface;

class BeforeNewApplicationServiceListener implements EventListenerInterface
{
  public function __invoke($emitter, string $eventId)
  {
    $postData = $emitter->getPostData();

    if (empty($postData))
    {
      return;
    }

    if (isset($postData[ApplicationForm::ATTR_PHONE]))
    {
      $this->modifyPhone($emitter);
    }
  }

  protected function modifyPhone($emitter)
  {
    $postData = $emitter->getPostData();
    $phone = $postData[ApplicationForm::ATTR_PHONE];

    if (strpos($phone, '+48') === false && strpos($phone, '+372') === false)
    {
      $phone = sprintf('+48%d', $phone);
    }

    $postData[ApplicationForm::ATTR_PHONE] = $phone;
    $emitter->setPostData($postData);

    return $phone;
  }
}