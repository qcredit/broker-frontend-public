<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 15.03.18
 * Time: 16:08
 */

namespace App\Controller;

use App\Models\User;
use Broker\Repository\UserRepositoryInterface;
use Doctrine\ORM\Cache\DefaultEntityHydrator;
use Doctrine\ORM\Query;
use JsonSchema\Validator;

class UserController
{
  protected $view;
  protected $userRepository;
  protected $entityManager;

  public function __construct($view)
  {
    $this->view = $view;
  }

  public function listUsers($request, $response, $args)
  {
    $validator = new Validator();

    $schema = <<<'JSON'
{
      "title": "Person",
    "type": "object",
    "properties": {
      "firstName": {
        "type": "string"
        },
        "lastName": {
        "type": "string"
        },
        "age": {
        "description": "Age in years",
            "type": "integer",
            "minimum": 0
        }
    },
    "required": ["firstName", "lastName"]
}
JSON;
    $validator->validate(json_decode('{"firstName": "Pamela"}'), json_decode($schema));
    print_r($validator->getErrors());

    $data = [];
    return $this->view->render($response, 'user/index.twig', $data);
  }
}