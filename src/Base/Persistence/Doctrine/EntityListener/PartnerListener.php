<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 25.05.18
 * Time: 11:13
 */

namespace App\Base\Persistence\Doctrine\EntityListener;
use App\Base\Repository\PartnerDataMapperRepository;
use Broker\Domain\Entity\Partner;

class PartnerListener
{
  public function postLoad(Partner $partner, $event)
  {
    $dataMapperRepository = new PartnerDataMapperRepository();

    try {
      $dataMapper = $dataMapperRepository->getDataMapperByPartnerId($partner->getIdentifier());
      $partner->setDataMapper($dataMapper);
    }
    catch (\Exception $ex)
    {

    }
  }
}