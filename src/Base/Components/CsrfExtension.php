<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 16.04.18
 * Time: 15:55
 */

namespace App\Base\Components;

use Slim\Csrf\Guard;

class CsrfExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
  /**
   * @var \Slim\Csrf\Guard
   */
  protected $csrf;

  /**
   * CsrfExtension constructor.
   * @param Guard $csrf
   */
  public function __construct(Guard $csrf)
  {
    $this->csrf = $csrf;
  }

  /**
   * @return array
   */
  public function getGlobals()
  {
    // CSRF token name and value
    $csrfNameKey = $this->csrf->getTokenNameKey();
    $csrfValueKey = $this->csrf->getTokenValueKey();
    $csrfName = $this->csrf->getTokenName();
    $csrfValue = $this->csrf->getTokenValue();

    return [
      'csrf'   => [
        'keys' => [
          'name'  => $csrfNameKey,
          'value' => $csrfValueKey
        ],
        'name'  => $csrfName,
        'value' => $csrfValue
      ]
    ];
  }

  /**
   * @return string
   */
  public function getName()
  {
    return 'slim/csrf';
  }
}