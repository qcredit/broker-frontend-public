<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 02.04.18
 * Time: 12:49
 */

namespace App\Controller;

use Slim\Views\Twig;

class AdminController
{
  /**
   * @var Twig
   */
  protected $view;

  public function __construct(Twig $view)
  {
    $this->view = $view;
  }

  /**
   * @param $request
   * @param $response
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function indexAction($request, $response)
  {
    $data = [
      'stats' =>
        [
          'day' => [
            'total' => 200,
            'acceptedApps' => 125,
            'paidOutApps' => 75,
            'rejectedApps' => 53,
            'inProcessApps' => 22
          ],
          'week' => [
            'total' => 1200,
            'acceptedApps' => 800,
            'paidOutApps' => 695,
            'rejectedApps' => 300,
            'inProcessApps' => 100
          ],
          'month' => [
            'total' => 4900,
            'acceptedApps' => 4600,
            'paidOutApps' => 4100,
            'rejectedApps' => 400,
            'inProcessApps' => 100
          ]
      ],
      'partners' => [
        [
          'partner' => 'Aasakredit',
          'totalApps' => 50,
          'acceptedApps' => 45,
          'paidOutApps' => 40,
          'rejectedApps' => 12,
          'inProcessApps' => 5
        ],
        [
          'partner' => 'Ferratum',
          'totalApps' => 66,
          'acceptedApps' => 50,
          'paidOutApps' => 45,
          'rejectedApps' => 16,
          'inProcessApps' => 0
        ]
      ]
    ];

    return $this->view->render($response,'admin/index.twig', $data);
  }

  /**
   * @param $request
   * @param $response
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function partnersAction($request, $response)
  {
    $data['partners'] = [
      [
        'id' => 1,
        'name' => 'Aasakredit',
        'commission' => 10
      ],
      [
        'id' => 2,
        'name' => 'Ferratum',
        'commission' => 12
      ],
      [
        'id' => 3,
        'name' => 'Vivus',
        'commission' => 11
      ]
    ];

    return $this->view->render($response, 'admin/partners.twig', $data);
  }

  /**
   * @param $request
   * @param $response
   * @param $args
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function partnerAction($request, $response, $args)
  {
    $data = [
      'partner' => [
        'id' => $args['id'],
        'name' => 'Aasakredit'
      ],
      'applications' => [
        [
          'id' => 100,
          'amount' => 2000,
          'reference' => 21312312,
          'status' => 'Accepted'
        ],
        [
          'id' => 101,
          'amount' => 3000,
          'reference' => 98234234,
          'status' => 'Accepted'
        ],
        [
          'id' => 102,
          'amount' => 3500,
          'reference' => 6803241,
          'status' => 'Rejected'
        ],
        [
          'id' => 103,
          'amount' => 4200,
          'reference' => 3460941,
          'status' => 'In process'
        ]
      ]
    ];

    return $this->view->render($response, 'admin/partner.twig', $data);
  }

  /**
   * @param $request
   * @param $response
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function applicationsAction($request, $response)
  {
    $data = [
      'applications' => [
        [
          'id' => 1000,
          'name' => 'Slava Bogu',
          'amount' => 8000,
          'term' => 18,
          'status' => 'Accepted'
        ],
        [
          'id' => 1001,
          'name' => 'Kert Ämmapets',
          'amount' => 4400,
          'term' => 12,
          'status' => 'Accepted'
        ],
        [
          'id' => 1002,
          'name' => 'Sviit Hõum',
          'amount' => 4600,
          'term' => 12,
          'status' => 'Rejected'
        ],
        [
          'id' => 1003,
          'name' => 'Aksel E. Rant',
          'amount' => 7200,
          'term' => 24,
          'status' => 'In process'
        ],
        [
          'id' => 1004,
          'name' => 'Vassili Jaava',
          'amount' => 100,
          'term' => 36,
          'status' => 'Rejected'
        ]
      ]
    ];

    return $this->view->render($response, 'admin/applications.twig', $data);
  }

  /**
   * @param $request
   * @param $response
   * @param $args
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function applicationAction($request, $response, $args)
  {
    $data = [
      'application' => [
        'id' => $args['id'],
        'loanAmount' => 5000,
        'loanTerm' => 24,
        'insurance' => true,
        'firstName' => 'Maizi',
        'lastName' => 'Pulgad',
        'pin' => '324239801523',
        'phone' => '+3725129350',
        'email' => 'maizi@pulgad.ee',
        'account' => 'EE22150123053202',
        'street' => 'Elm Street',
        'houseNr' => '21',
        'apartmentNr' => '3',
        'zip' => '50112',
        'city' => 'Tallinn',
        'state' => 'Harjumaa',
        'country' => 'Estonia',
        'residentialType' => 'Own',
        'propertyType' => 'Apartment',
        'incomeSourceType' => 'Employed',
        'position' => 'Worker',
        'employerName' => 'Eesti Vabariigi Valitsus',
        'netPerMonth' => 3200,
        'loanPurposeType' => 'Bills',
        'documentNr' => 'AA233120',
        'educationType' => 'MBA',
        'maritalStatus' => 'Single',
        'createdAt' => '2018-04-01 10:00:00'
      ],
      'offers' => [
        [
          'partnerId' => 1,
          'partnerName' => 'Aasakredit',
          'amount' => 3000,
          'id' => 123123,
          'status' => 'Accepted'
        ],
        [
          'partnerId' => 2,
          'partnerName' => 'Ferratum',
          'amount' => 3200,
          'id' => 414121,
          'status' => 'Rejected'
        ]
      ]
    ];

    return $this->view->render($response, 'admin/application.twig', $data);
  }

  /**
   * @param $request
   * @param $response
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function loginAction($request, $response)
  {
    $data = [];

    if ($request->isPost())
    {
      $data = [
        'errors' => [
          'email' => 'No account with this e-mail.'
        ]
      ];
    }

    return $this->view->render($response, 'admin/login.twig', $data);
  }

  /**
   * @param $request
   * @param $response
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function usersAction($request, $response)
  {
    $data = [
      'users' => [
        [
          'id' => 1,
          'email' => 'someone@gmail.com',
          'accessLevel' => 1,
          'createdAt' => '2018-04-01 12:00:00'
        ],
        [
          'id' => 2,
          'email' => 'another@gmail.com',
          'accessLevel' => 2,
          'createdAt' => '2018-04-02 13:12:11'
        ]
      ]
    ];

    return $this->view->render($response, 'admin/users.twig', $data);
  }
}