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
    protected $_select = null;

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setSelect(array $value = null)
    {
        $this->_select = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getSelect()
    {
        return $this->_select;
    }

    /**
     * @var array
     */
    protected $_order = null;

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
        $this->_order = $toSet;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @var array
     */
    protected $_filter = null;

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setFilter(array $value = null)
    {
        $this->_filter = $value;

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
        return $this->_filter;
    }

    /**
     * @var int
     */
    protected $_limit = null;

    /**
     * @param int $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setLimit($value)
    {
        $this->_limit = $value === null ? null : (int) $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * @var int
     */
    protected $_offset = null;

    /**
     * @param int $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setOffset($value)
    {
        $this->_offset = $value === null ? null : (int) $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * @var string
     */
    protected $_index = null;

    /**
     * @param string $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setIndex($value)
    {
        $this->_index = $value === null ? null : trim($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->_index;
    }

    /**
     * @return \marvin255\bxar\IQuery
     */
    public function clear()
    {
        $this->_index = null;
        $this->_offset = null;
        $this->_limit = null;
        $this->_filter = null;
        $this->_order = null;
        $this->_select = null;

        return $this;
    }
}
