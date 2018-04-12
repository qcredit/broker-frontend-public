<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 12.04.18
 * Time: 11:06
 */

namespace App\Middleware;

class Session extends \Slim\Middleware\Session
{
  /**
   * Start session
   */
  protected function startSession()
  {
    $settings = $this->settings;
    $name = $settings['name'];

    session_write_close();
    session_set_cookie_params(
      $settings['lifetime'],
      $settings['path'],
      $settings['domain'],
      $settings['secure'],
      $settings['httponly']
    );

    $inactive = session_status() === PHP_SESSION_NONE;

    if ($inactive) {
      // Refresh session cookie when "inactive",
      // else PHP won't know we want this to refresh
      if ($settings['autorefresh'] && isset($_COOKIE[$name])) {
        setcookie(
          $name,
          $_COOKIE[$name],
          time() + $settings['lifetime'],
          $settings['path'],
          $settings['domain'],
          $settings['secure'],
          $settings['httponly']
        );
      }
    }

    session_name($name);

    $handler = $settings['handler'];
    if ($handler) {
      if (!($handler instanceof SessionHandlerInterface)) {
        $handler = new $handler;
      }
      session_set_save_handler($handler, true);
    }

    session_cache_limiter(false);
    if ($inactive) {
      session_start();
    }
  }
}