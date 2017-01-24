<?php

namespace marvin255\bxar\abstracts;

/**
 * Абстрактный класс, который реализует базовые функции IQuery.
 */
abstract class Query
{
    /**
     * @var array
     */
    protected $select = null;

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setSelect(array $value = null)
    {
        $this->select = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @var array
     */
    protected $order = null;

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setOrder(array $value = null)
    {
        if (is_array($value)) {
            $toSet = [];
            foreach ($value as $key => $sort) {
                if (is_numeric($key)) {
                    $key = $sort;
                    $sort = 'asc';
                } else {
                    $sort = strtolower($sort);
                }
                if ($sort !== 'asc' && $sort !== 'desc') {
                    continue;
                }
                $toSet[trim($key)] = $sort;
            }
        } else {
            $toSet = null;
        }
        $this->order = $toSet;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @var array
     */
    protected $filter = null;

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setFilter(array $value = null)
    {
        $this->filter = $value;

        return $this;
    }

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function andFilter(array $value)
    {
        $filter = $this->getFilter();
        $filter = is_array($filter) ? $filter : [];
        $this->setFilter(array_merge($filter, $value));

        return $this;
    }

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function orFilter(array $value)
    {
        $filter = $this->getFilter();
        $filter = is_array($filter) ? $filter : [];
        $this->setFilter(array_merge($filter, $value));

        return $this;
    }

    /**
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @var int
     */
    protected $limit = null;

    /**
     * @param int $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setLimit($value)
    {
        $this->limit = $value === null ? null : (int) $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @var int
     */
    protected $offset = null;

    /**
     * @param int $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setOffset($value)
    {
        $this->offset = $value === null ? null : (int) $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @var string
     */
    protected $index = null;

    /**
     * @param string $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setIndex($value)
    {
        $this->index = $value === null ? null : trim($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return \marvin255\bxar\IQuery
     */
    public function clear()
    {
        $this->index = null;
        $this->offset = null;
        $this->limit = null;
        $this->filter = null;
        $this->order = null;
        $this->select = null;

        return $this;
    }
}
