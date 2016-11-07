<?php

namespace marvin255\bxar;

/**
 * Трэйт, который реализует базовые функции IQuery.
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
     * @return \marvin255\bxar\IQuery
     */
    public function setSelect(array $value = array())
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
    public function setOrder(array $value = array())
    {
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
    public function setFilter(array $value = array())
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
        $this->_filter = array_merge(
            $this->_filter ? $this->_filter : [],
            $value
        );

        return $this;
    }

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function orFilter(array $value)
    {
        $this->_filter = array_merge(
            $this->_filter ? $this->_filter : [],
            $value
        );

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
     * @return \marvin255\bxar\IQuery
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
     * @return \marvin255\bxar\IQuery
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

    /**
     * @var \marvin255\bxar\IRepo
     */
    protected $_repo = null;

    /**
     * @param \marvin255\bxar\IRepo $repo
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setRepo(IRepo $repo)
    {
        $this->_repo = $repo;

        return $this;
    }

    /**
     * @return \marvin255\bxar\IRepo
     */
    public function getRepo()
    {
        return $this->_repo;
    }

    /**
     * Возвращает одну запись из хранилища.
     * Shortcut для соответствующего метода хранилища.
     *
     * @return \marvin255\bxar\IModel|null
     */
    public function search()
    {
        return $this->getRepo()->search($this);
    }

    /**
     * Возвращает массив записей из хранилища
     * Shortcut для соответствующего метода хранилища.
     *
     * @return array
     */
    public function searchAll()
    {
        return $this->getRepo()->searchAll($this);
    }

    /**
     * Возвращает количество элементов в хранилище.
     * Shortcut для соответствующего метода хранилища.
     *
     * @return int
     */
    public function count()
    {
        return $this->getRepo()->count($this);
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
        $this->_repo = null;

        return $this;
    }
}
