<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 10.04.18
 * Time: 10:38
 */

namespace App\Base\Components;

use Slim\Http\Response;

abstract class AbstractController
{
  /**
   * @param Response $response
   * @param $viewFile
   * @param array $data
   * @return mixed
   */
  public function render(Response $response, $viewFile, $data = [])
  {
    $this->prepareFlashes($data);

    return $this->getContainer()->get('view')->render($response, $viewFile, $data);
  }

  /**
   * @param $data
   */
  public function prepareFlashes(&$data)
  {
    $flash = $this->getFlash();

    if ($flash->hasMessage('success'))
    {
      $data['flash']['success'] = $flash->getFirstMessage('success');
    }
    elseif ($flash->hasMessage('error'))
    {
      $data['flash']['error'] = $flash->getFirstMessage('error');
    }
  }

  /**
   * @return mixed
   */
  protected function getFlash()
  {
    return $this->getContainer()->get('flash');
  }
}