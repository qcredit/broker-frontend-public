<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 02.04.18
 * Time: 12:42
 */

namespace App\Controller;

use App\Component\AbstractController;
use App\Model\ContactForm;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ContactController extends AbstractController
{
  /**
   * @var Container
   */
  protected $container;
  /**
   * @var ContactForm
   */
  protected $contactForm;

  /**
   * @return Container
   * @codeCoverageIgnore
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @return ContactForm
   * @codeCoverageIgnore
   */
  public function getContactForm()
  {
    return $this->contactForm;
  }

  /**
   * @return Twig
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getView()
  {
    return $this->getContainer()->get('view');
  }

  /**
   * ContactController constructor.
   * @param Container $container
   * @param ContactForm $contactForm
   */
  public function __construct(Container $container, ContactForm $contactForm)
  {
    $this->container = $container;
    $this->contactForm = $contactForm;
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param array $args
   * @return mixed
   * @throws \Broker\System\Error\InvalidConfigException
   * @throws \Exception
   */
  public function indexAction(Request $request, Response $response, $args = [])
  {
    $data = [
      'sent' => false
    ];
    $contactForm = $this->getContactForm();

    if ($request->isPost())
    {
      $postData = $request->getParsedBody();
      unset($postData['csrf_name']);
      unset($postData['csrf_value']);
      if ($contactForm->load($postData) && $contactForm->validate() && $contactForm->send())
      {
        $data['sent'] = true;
      }
    }

    $data['contact'] = $contactForm->getModel();

    return $this->render($response, 'contact.twig', $data);
  }
}
