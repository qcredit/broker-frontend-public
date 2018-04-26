<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 26.04.18
 * Time: 15:19
 */

namespace App\Base\Repository;

use App\Base\Interfaces\MessageTemplateRepositoryInterface;
use Broker\Domain\Entity\Message;
use Slim\Container;
use Slim\Views\Twig;

class MessageTemplateRepository implements MessageTemplateRepositoryInterface
{
  /**
   * @var Container
   */
  protected $container;

  /**
   * MessageTemplateRepository constructor.
   * @param Container $container
   */
  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  /**
   * @return Container
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @param Container $container
   * @return MessageTemplateRepository
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @return Twig
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getView(): Twig
  {
    return $this->getContainer()->get('view');
  }

  /**
   * @param string $path
   * @param array $arguments
   * @return string
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getTemplateByPath(string $path, array $arguments = []): string
  {
    $view = $this->getView();

    return $view->fetch($path, $arguments);
  }
}