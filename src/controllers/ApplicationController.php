<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 19.03.18
 * Time: 16:58
 */

namespace App\Controllers;

use Broker\Domain\Service\PreparePartnerRequestsService;
use Broker\Repository\ApplicationRepositoryInterface;
use Slim\Views\Twig;

class ApplicationController
{
  protected $view;

  public function __construct(
    Twig $view)
  {
    $this->view = $view;
  }

  /**
   * @param $request
   * @param $response
   * @param $args
   * @return \Psr\Http\Message\ResponseInterface
   * @throws \Exception
   */
  public function indexAction($request, $response, $args)
  {
    $data = [];
    if ($request->isPost())
    {
      $data['errors'] = [
        'firstName' => 'This field is required'
      ];

      if (!isset($data['errors']) || empty($data['errors']))
      {
        return $response->withRedirect(sprintf('application/%s', $app->getApplicationHash()));
      }
    }

    return $this->view->render($response, 'application/form.twig', $data);
  }

  /**
   * @param $request
   * @param $response
   * @param $args
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function offerListAction($request, $response, $args)
  {
    $data = [
      'offers' => [
        [
          'partner' => 1,
          'amount' => 2400,
          'interest' => 19,
          'term' => 24,
          'apr' => 238,
          'logo' => 'url/to/logo.png'
        ],
        [
          'partner' => 2,
          'amount' => 2600,
          'interest' => 20,
          'term' => 24,
          'apr' => 245,
          'logo' => 'url/to/logo.png'
        ]
      ]
    ];

    return $this->view->render($response, 'application/offer-list.twig', $data);
  }
}
