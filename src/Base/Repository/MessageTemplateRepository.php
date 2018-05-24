<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 26.04.18
 * Time: 15:19
 */

namespace App\Base\Repository;

use App\Model\ApplicationForm;
use Broker\Domain\Interfaces\Repository\MessageTemplateRepositoryInterface;
use Broker\Domain\Entity\Application;
use Broker\Domain\Entity\Message;
use Broker\Domain\Entity\Offer;
use Broker\Domain\Interfaces\Factory\MessageFactoryInterface;
use Slim\App;
use Slim\Container;
use Slim\Views\Twig;

class MessageTemplateRepository implements MessageTemplateRepositoryInterface
{
  /**
   * @var Container
   */
  protected $container;
  /**
   * @var MessageFactoryInterface
   */
  protected $messageFactory;

  /**
   * MessageTemplateRepository constructor.
   * @param Container $container
   * @param MessageFactoryInterface $messageFactory
   */
  public function __construct(Container $container, MessageFactoryInterface $messageFactory)
  {
    $this->container = $container;
    $this->messageFactory = $messageFactory;
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
   * @return MessageFactoryInterface
   * @codeCoverageIgnore
   */
  public function getMessageFactory()
  {
    return $this->messageFactory;
  }

  /**
   * @param MessageFactoryInterface $messageFactory
   * @return MessageTemplateRepository
   * @codeCoverageIgnore
   */
  public function setMessageFactory(MessageFactoryInterface $messageFactory)
  {
    $this->messageFactory = $messageFactory;
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

  /**
   * @param Application $application
   * @return Message
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getOfferLinkMessage(Application $application)
  {
    $domain = getenv('ENV_TYPE') == 'production' ? 'https://www.qcredit.pl' : (getenv('ENV_TYPE') == 'testserver' ? 'https://www-test.qcredit.pl' : 'http://localhost:8100');
    $message = $this->getMessageFactory()->create();
    $message->setTitle(_('Offers for your application'))
      ->setType(Message::MESSAGE_TYPE_EMAIL)
      ->setBody($this->generateEmailContent('mail/offer-link.twig', [
        'application' => $application,
        'title' => $message->getTitle(),
        'link' => sprintf('%s/application/%s', $domain, $application->getApplicationHash())
      ]))
      ->setRecipient($application->getEmail());

    return $message;
  }

  /**
   * @param Application $application
   * @return Message
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getOfferReminderMessage(Application $application)
  {
    $domain = getenv('ENV_TYPE') == 'production' ? 'https://www.qcredit.pl' : (getenv('ENV_TYPE') == 'testserver' ? 'https://www-test.qcredit.pl' : 'http://localhost:8100');
    $message = $this->getMessageFactory()->create();
    $message->setType(Message::MESSAGE_TYPE_EMAIL)
      ->setTitle(_('Check out these offers for you loan application!'))
      ->setRecipient($application->getEmail())
      ->setBody($this->generateEmailContent('mail/offer-reminder.twig', [
        'application' => $application,
        'title' => $message->getTitle(),
        'link' => sprintf('%s/application/%s', $domain, $application->getApplicationHash())
      ]));

    return $message;
  }

  /**
   * @param Application $application
   * @return Message
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getOfferReminderSmsMessage(Application $application)
  {
    $message = $this->getMessageFactory()->create();
    $message->setType(Message::MESSAGE_TYPE_SMS)
      ->setRecipient($application->getPhone())
      ->setBody($this->getTemplateByPath('sms/offer-reminder.twig', ['application' => $application]));

    return $message;
  }

  /**
   * @param Offer $offer
   * @return Message
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getOfferConfirmationMessage(Offer $offer)
  {
    $message = $this->getMessageFactory()->create();
    $message->setTitle(_('Thank you for choosing us!'))
      ->setRecipient($offer->getApplication()->getEmail())
      ->setType(Message::MESSAGE_TYPE_EMAIL)
      ->setBody($this->generateEmailContent('mail/offer-confirmation.twig', [
        'offer' => $offer,
        'title' => $message->getTitle()
      ]));

    return $message;
  }

  /**
   * @param $data
   * @param $sendTo
   * @return Message
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getContactFormMessage($data, $sendTo)
  {
    $message = $this->getMessageFactory()->create();
    $message->setType(Message::MESSAGE_TYPE_EMAIL)
      ->setRecipient($sendTo)
      ->setBody($this->generateEmailContent('mail/contact-form.twig', $data))
      ->setTitle(_('Message from website'));

    return $message;
  }

  /**
   * @param string $template
   * @param array $params
   * @return string
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function generateEmailContent(string $template, array $params = [])
  {
    $twig = $this->getView();

    return $twig->fetch($template, $params);
  }
}