<?php

namespace marvin255\bxar\query;

/**
 * Базовый класс для запроса к хранилищу.
 *
 * @see \marvin255\bxar\query\QueryInterface
 */
class Query implements QueryInterface
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
        $this->order = $value;

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
        $this->limit = $value;

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
        $this->offset = $value;

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
        $this->index = $value;

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
