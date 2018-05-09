<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 08.05.18
 * Time: 10:01
 */

namespace App\Controller;

use Broker\System\Log;
use Slim\Http\Request;
use Slim\Http\Response;

class ApiController
{
  public function updateAction(Request $request, Response $response, $args)
  {
    Log::debug('INCOMING!!!', [$request->getHeaders()]);

    return $response->withJson(['message' => 'OK']);
  }
}