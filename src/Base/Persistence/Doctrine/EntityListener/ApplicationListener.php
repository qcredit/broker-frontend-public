<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 06.06.18
 * Time: 09:02
 */

namespace App\Base\Persistence\Doctrine\EntityListener;

use App\Model\ApplicationForm;
use Broker\Domain\Entity\Application;

class ApplicationListener
{
  public function prePersist(Application $application, $event)
  {
    $errors = $application->getErrors();
    if (array_key_exists(ApplicationForm::ATTR_PHONE, $errors) && array_key_exists(ApplicationForm::ATTR_EMAIL, $errors))
    {
      throw new \Exception('Cannot save app without phone or email!');
    }
  }
}