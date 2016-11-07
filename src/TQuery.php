<?php

namespace bxar;

/**
 * Трэйт, который реализует базовые функции IQuery
 */
trait TQuery
{
    /**
     * @var array
     */
    protected $_select = null;

    /**
     * @param array $value
     *
     * @return \bxar\IQuery
     */
    public function setSelect(array $value)
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
     * @return \bxar\IQuery
     */
    public function setOrder(array $value)
    {
        $toSet = [];
        foreach ($value as $key => $sort) {
            $sort = strtolower($sort);
            if ($sort !== 'asc' && $sort !== 'desc') continue;
            $toSet[trim($key)] = $sort;
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
     * @return \bxar\IQuery
     */
    public function setFilter(array $value)
    {
        $this->_filter = $value;

        return $this;
    }

    /**
     * @param array $value
     *
     * @return \bxar\IQuery
     */
    public function andFilter(array $value)
    {
        $this->_filter = array_merge($this->_filter, $value);

        return $this;
    }

    /**
     * @param array $value
     *
     * @return \bxar\IQuery
     */
    public function orFilter(array $value)
    {
        $this->_filter = array_merge($this->_filter, $value);

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
     * @return \bxar\IQuery
     */
    public function setLimit($value)
    {
        $this->_limit = (int) $value;

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
     * @return \bxar\IQuery
     */
    public function setOffset($value)
    {
        $this->_offset = (int) $value;

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
     * @return \bxar\IQuery
     */
    public function setIndex($value)
    {
        $this->_index = trim($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->_index;
    }
}
