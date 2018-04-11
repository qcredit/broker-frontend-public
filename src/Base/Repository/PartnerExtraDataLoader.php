<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 06.04.18
 * Time: 15:16
 */

namespace App\Base\Repository;

use Broker\Domain\Entity\Partner;
use Broker\Domain\Interfaces\Repository\PartnerDataMapperRepositoryInterface;
use Broker\System\Error\InvalidConfigException;

class PartnerExtraDataLoader
{
  /**
   * @var PartnerDataMapperRepositoryInterface
   */
  protected $partnerDataMapper;

  /**
   * @return PartnerDataMapperRepositoryInterface
   */
  public function getPartnerDataMapper()
  {
    return $this->partnerDataMapper;
  }

  /**
   * @param PartnerDataMapperRepositoryInterface $partnerDataMapper
   * @return PartnerExtraDataLoader
   */
  public function setPartnerDataMapper(PartnerDataMapperRepositoryInterface $partnerDataMapper)
  {
    $this->partnerDataMapper = $partnerDataMapper;
    return $this;
  }

  /**
   * PartnerExtraDataLoader constructor.
   * @param PartnerDataMapperRepositoryInterface $partnerDataMapperRepository
   */
  public function __construct(PartnerDataMapperRepositoryInterface $partnerDataMapperRepository)
  {
    $this->partnerDataMapper = $partnerDataMapperRepository;
  }

  /**
   * @param Partner $partner
   * @return Partner
   * @throws \Exception
   */
  public function loadExtraConfiguration(Partner $partner)
  {
    try
    {
      $mapper = $this->getPartnerDataMapper()->getDataMapperByPartnerId($partner->getIdentifier());
      $partner->load($mapper->getConfig());
    }
    catch (InvalidConfigException $ex)
    {

    }

    return $partner;
  }

  /**
   * @param array $partners
   * @return array
   * @throws \Exception
   */
  public function bulkLoadExtraConfiguration(array $partners)
  {
    array_walk($partners, [$this, 'loadExtraConfiguration']);

    return $partners;
  }
}