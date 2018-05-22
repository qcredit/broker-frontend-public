<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 22.05.18
 * Time: 11:24
 */

namespace App\Component;

use Slim\Http\Request;

class Pagination
{
  /**
   * @var int
   */
  private $totalCount;
  /**
   * @var int
   */
  private $limit;
  /**
   * @var int
   */
  private $defaultLimit = 5;
  /**
   * @var int
   */
  private $offset = 0;
  /**
   * @var Request
   */
  private $request;
  /**
   * @var string
   */
  private $pageQueryParam = 'page';
  /**
   * @var int
   */
  private $currentPage = 1;
  /**
   * @var int|null
   */
  private $nextPage;
  /**
   * @var int|null
   */
  private $previousPage;
  /**
   * @var int
   */
  private $totalPages;

  /**
   * Pagination constructor.
   * @param Request $request
   * @param int $totalCount
   * @param int|null $limit
   */
  public function __construct(Request $request, int $totalCount, int $limit = null)
  {
    $this->request = $request;
    $this->totalCount = $totalCount;
    $this->limit = $limit;

    $this->setParameters();
  }

  /**
   * @return int
   * @codeCoverageIgnore
   */
  public function getTotalCount()
  {
    return $this->totalCount;
  }

  /**
   * @return int
   * @codeCoverageIgnore
   */
  public function getLimit()
  {
    return $this->limit ?? $this->defaultLimit;
  }

  /**
   * @return int
   * @codeCoverageIgnore
   */
  public function getOffset()
  {
    return $this->offset;
  }

  /**
   * @return int
   * @codeCoverageIgnore
   */
  public function getCurrentPage()
  {
    return $this->currentPage;
  }

  /**
   * @return int|null
   * @codeCoverageIgnore
   */
  public function getNextPage()
  {
    return $this->nextPage;
  }

  /**
   * @return string
   */
  public function getNextPageUrl()
  {
    return $this->getNextPage() !== null ? $this->buildPageUrl($this->getNextPage()) : '';
  }

  /**
   * @return string
   */
  public function getNextPageLink()
  {
    $nextPageUrl = $this->getNextPageUrl();

    return empty($nextPageUrl) ? $nextPageUrl : sprintf('<a href="%s">%s</a>', $nextPageUrl, $this->getNextPage());
  }

  /**
   * @return int|null
   * @codeCoverageIgnore
   */
  public function getPreviousPage()
  {
    return $this->previousPage;
  }

  /**
   * @return string
   */
  public function getPreviousPageUrl()
  {
    return isset($this->previousPage) ? $this->buildPageUrl($this->getPreviousPage()) : '';
  }

  /**
   * @return string
   */
  public function getPreviousPageLink()
  {
    $previousPageUrl = $this->getPreviousPageUrl();

    return empty($previousPageUrl) ? $previousPageUrl : sprintf('<a href="%s">%s</a>', $previousPageUrl, $this->getPreviousPage());
  }

  /**
   * @return int
   * @codeCoverageIgnore
   */
  public function getTotalPages()
  {
    return $this->totalPages;
  }

  /**
   * @return Request
   */
  protected function getRequest()
  {
    return $this->request;
  }

  protected function setParameters()
  {
    $this->setCurrentPage();
    $this->setTotalPages();
    $this->setNextPage();
    $this->setPreviousPage();
    $this->setCurrentOffset();
  }

  protected function setCurrentPage()
  {
    $this->currentPage = $this->getQueryParam($this->pageQueryParam, 1);
  }

  protected function setTotalPages()
  {
    $this->totalPages = ceil($this->getTotalCount() / $this->getLimit());
  }

  protected function setNextPage()
  {
    if ($this->getTotalPages() - 1 >= $this->getCurrentPage())
    {
      $this->nextPage = $this->getCurrentPage() + 1;
    }
  }

  protected function setPreviousPage()
  {
    if ($this->getCurrentPage() > 1)
    {
      $this->previousPage = $this->getCurrentPage() - 1;
    }
  }

  protected function setCurrentOffset()
  {
    $this->offset = $this->getPreviousPage() * $this->getLimit();
  }

  /**
   * @param string $paramName
   * @param null $default
   * @return mixed
   */
  protected function getQueryParam(string $paramName, $default = null)
  {
    return $this->getRequest()->getQueryParam($paramName, $default);
  }

  /**
   * @param int $pageNumber
   * @return string
   */
  protected function buildPageUrl(int $pageNumber)
  {
    $uri = $this->getRequest()->getUri();
    $path = $uri->getPath();
    $query = $uri->getQuery();

    parse_str($query, $output);

    $output['page'] = $pageNumber;

    return sprintf('%s?%s', $path, http_build_query($output));
  }
}