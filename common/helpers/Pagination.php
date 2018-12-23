<?php

namespace app\common\helpers;
/**
 *
 * 简单计算分页参数
 * Class Pagination
 */
class Pagination
{

    /**
     * @var string the route of the controller action for displaying the paged contents.
     */

    public $totalCount = 0;
    /**
     * @var int the default page size. This property will be returned by [[pageSize]] when page size
     */
    public $defaultPageSize = 20;
    /**
     * @var array|false the page size limits. The first array element stands for the minimal page size, and the second
     */
    public $pageSizeLimit = [1, 50];

    /**
     * @var int number of items on each page.
     */
    private $pageSize;

    private $page;

    /**
     * @return int number of pages
     */
    public function getPageCount()
    {
        $pageSize = $this->getPageSize();
        if ($pageSize < 1) {
            return $this->totalCount > 0 ? 1 : 0;
        }
        $totalCount = $this->totalCount < 0 ? 0 : (int)$this->totalCount;
        return (int)(($totalCount + $pageSize - 1) / $pageSize);
    }


    /**
     * Returns the zero-based current page number.
     * @param bool $recalculate whether to recalculate the current page based on the page size and item count.
     * @return int the zero-based current page number.
     * @throws \Exception
     */
    public function getPage($recalculate = false)
    {
        if ($this->page === null) {
            throw  new \Exception('请设置当前页码!');
        }
        return $this->page;
    }

    /**
     * Sets the current page number.
     * @param int $value the zero-based index of the current page.
     * @param bool $validatePage whether to validate the page number. Note that in order
     * to validate the page number, both [[validatePage]] and this parameter must be true.
     */
    public function setPage($value, $validatePage = false)
    {
        if ($value === null) {
            $this->page = null;
        } else {
            $value = (int)$value;
            if ($validatePage && $this->validatePage) {
                $pageCount = $this->getPageCount();
                if ($value >= $pageCount) {
                    $value = $pageCount - 1;
                }
            }
            if ($value < 0) {
                $value = 0;
            }
            $this->page = $value;
        }
    }

    /**
     * Returns the number of items per page.
     * By default, this method will try to determine the page size by [[pageSizeParam]] in [[params]].
     * If the page size cannot be determined this way, [[defaultPageSize]] will be returned.
     * @return int the number of items per page. If it is less than 1, it means the page size is infinite,
     * and thus a single page contains all items.
     * @see pageSizeLimit
     */
    public function getPageSize()
    {
        if ($this->pageSize === null) {
            if (empty($this->pageSizeLimit)) {
                $pageSize = $this->defaultPageSize;
                $this->setPageSize($pageSize);
            }
        }
        return $this->pageSize;
    }

    /**
     * @param int $value the number of items per page.
     * @param bool $validatePageSize whether to validate page size.
     */
    public function setPageSize($value, $validatePageSize = false)
    {
        if ($value === null) {
            $this->pageSize = null;
        } else {
            $value = (int)$value;
            if ($validatePageSize && isset($this->pageSizeLimit[0], $this->pageSizeLimit[1])
                && count($this->pageSizeLimit) === 2) {
                if ($value < $this->pageSizeLimit[0]) {
                    $value = $this->pageSizeLimit[0];
                } elseif ($value > $this->pageSizeLimit[1]) {
                    $value = $this->pageSizeLimit[1];
                }
            }
            $this->pageSize = $value;
        }
    }

    /**
     * @return float|int
     * @throws \Exception
     */
    public function getOffset()
    {
        $pageSize = $this->getPageSize();
        return $pageSize < 1 ? 0 : $this->getPage() * $pageSize;
    }

    /**
     * @return int the limit of the data. This may be used to set the
     * LIMIT value for a SQL statement for fetching the current page of data.
     * Note that if the page size is infinite, a value -1 will be returned.
     */
    public function getLimit()
    {
        $pageSize = $this->getPageSize();
        return $pageSize < 1 ? -1 : $pageSize;
    }

    /**
     * 获取某个参数
     * @param $param
     * @return mixed
     * @throws \Exception
     */
    public function __get($param)
    {
        $methodName = 'get' . ucfirst($param);
        if (method_exists($this, $methodName)) {
            return call_user_func([$this, $methodName]);
        }

        throw  new \Exception($methodName . '不存在');
    }

    /**
     * 魔术方法设置参数
     * @param $name
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        $methodName = 'set' . $name;
        if (method_exists($this, $methodName)) {
            return call_user_func([$this, $methodName], $value);
        }
        throw  new \Exception($methodName . '不存在');

    }


    //初始化分页类参数
    public function __construct(array $params)
    {
        foreach ($params as $key => $value) {

            $methodKey = $key;
            $methodName = 'set' . ucfirst($methodKey);
            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            } else {
                $this->{$key} = $value;
            }
        }
    }

}