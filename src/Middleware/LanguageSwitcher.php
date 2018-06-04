<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 03.05.18
 * Time: 17:03
 */

namespace App\Middleware;

use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class LanguageSwitcher
{
  const COOKIE_LANGUAGE = 'lang';
  /**
   * @var App
   */
  protected $app;
  /**
   * @var Container
   */
  protected $container;
  /**
   * @var string
   */
  protected $domain = 'broker';
  /**
   * @var string
   */
  protected $locale;

  /**
   * @return App
   * @codeCoverageIgnore
   */
  public function getApp()
  {
    return $this->app;
  }

  /**
   * @param App $app
   * @return LanguageSwitcher
   * @codeCoverageIgnore
   */
  public function setApp(App $app)
  {
    $this->app = $app;
    return $this;
  }

  /**
   * @return Container
   * @codeCoverageIgnore
   */
  public function getContainer()
  {
    return $this->container;
  }

  /**
   * @param Container $container
   * @return LanguageSwitcher
   * @codeCoverageIgnore
   */
  public function setContainer(Container $container)
  {
    $this->container = $container;
    return $this;
  }

  /**
   * @return string
   * @codeCoverageIgnore
   */
  public function getDomain()
  {
    return $this->domain;
  }

  /**
   * @param string $domain
   * @return LanguageSwitcher
   * @codeCoverageIgnore
   */
  public function setDomain(string $domain)
  {
    $this->domain = $domain;
    return $this;
  }

  /**
   * @return string
   * @codeCoverageIgnore
   */
  public function getLocale()
  {
    return $this->locale;
  }

  /**
   * @param string $locale
   * @return LanguageSwitcher
   * @codeCoverageIgnore
   */
  public function setLocale(string $locale)
  {
    $this->locale = $locale;
    return $this;
  }

  /**
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function hasDefaultLanguage()
  {
    $settings = $this->getContainer()->get('settings');
    return (!empty($settings) && isset($settings['defaultLanguage']));
  }

  /**
   * @return mixed|null
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function getDefaultLanguage()
  {
    $settings = $this->getContainer()->get('settings');
    if ($this->hasDefaultLanguage())
    {
      return $settings['defaultLanguage'];
    }

    return null;
  }

  /**
   * LanguageSwitcher constructor.
   * @param App $application
   */
  public function __construct(App $application)
  {
    $this->app = $application;
    $this->container = $application->getContainer();
  }

  /**
   * @param Request $request
   * @param Response $response
   * @param $next
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   */
  public function __invoke(Request $request, Response $response, $next)
  {
    if ($this->isLanguageSetByCookie())
    {
      $this->setLanguageByCookie();
    }
    elseif ($this->hasDefaultLanguage())
    {
      $this->setLanguageByDefault();
    }
    else {
      $this->setLanguageByBrowser();
    }

    $response = $response->withAddedHeader('X-Locale', $this->getLocale());

    return $next($request, $response);
  }

  /**
   * @param string $language
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function setLanguage(string $language)
  {
    $domain = $this->getDomain();

    $this->putenv(sprintf('LC_ALL=%s.UTF-8', $language));
    if ($this->setSystemLocale(LC_ALL, sprintf('%s.utf8', $language)) === false)
    {
      //$this->getContainer()->get('logger')->debug(sprintf('Unable to set PHP locale (%s)!', $language));
      return false;
    }

    $this->bindTextDomain($domain, dirname(dirname(__DIR__)) . '/locale');
    $this->bindTextDomainCodeset($domain, 'UTF-8');
    $this->setTextDomain($domain);

    $this->setLocale($language);

    return true;
  }

  /**
   * @return mixed
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function isLanguageSetByCookie()
  {
    return $this->getContainer()->get('session')->get(self::COOKIE_LANGUAGE, false);
  }

  /**
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function setLanguageByCookie()
  {
    $language = $this->getContainer()->get('session')->get(self::COOKIE_LANGUAGE);
    return $this->setLanguage($language);
  }

  /**
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function setLanguageByBrowser()
  {
    $preferredLanguage = $this->getPreferredLanguage();

    if (!$preferredLanguage) return false;

    return $this->setLanguage($preferredLanguage);
  }

  /**
   * @return bool
   * @throws \Interop\Container\Exception\ContainerException
   */
  protected function setLanguageByDefault()
  {
    return $this->setLanguage($this->getDefaultLanguage());
  }

  /**
   * @return mixed|null
   */
  protected function getPreferredLanguage()
  {
    $languages = explode(',', $this->getBrowserLanguages());

    if (!preg_match('/[a-z]*-[A-Z]*/', $languages[0]))
    {
      return null;
    }

    return str_replace('-', '_', $languages[0]);
  }

  /**
   * @return mixed
   */
  protected function getBrowserLanguages()
  {
    return $_SERVER['HTTP_ACCEPT_LANGUAGE'];
  }

  /**
   * @param string $variable
   * @return bool
   */
  protected function putenv(string $variable)
  {
    return putenv($variable);
  }

  /**
   * @param int $category
   * @param string $locale
   * @return string
   */
  protected function setSystemLocale(int $category, string $locale)
  {
    return setlocale($category, $locale);
  }

  /**
   * @param string $domain
   * @param string $path
   * @return string
   */
  protected function bindTextDomain(string $domain, string $path)
  {
    return bindtextdomain($domain, $path);
  }

  /**
   * @param string $domain
   * @param string $codeset
   * @return string
   */
  protected function bindTextDomainCodeset(string $domain, string $codeset)
  {
    return bind_textdomain_codeset($domain, $codeset);
  }

  /**
   * @param string $domain
   * @return string
   */
  protected function setTextDomain(string $domain)
  {
    return textdomain($domain);
  }
}