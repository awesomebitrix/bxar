<?php

namespace bx\ar;

/**
 * Базовый класс для поиска моделей по запросу
 */
abstract class Finder implements \bx\ar\IFinder
{
	/**
	 * @var array сортиовка
	 */
	protected $_order = array();
	/**
	 * @var array условия для выборки
	 */
	protected $_filter = array();
	/**
	 * @var array условия для выборки
	 */
	protected $_limit = null;
	/**
	 * @var array условия для выборки
	 */
	protected $_offset = null;
	/**
	 * @var int время, на которое кэшировать запрос
	 */
	protected $_cache = null;
	/**
	 * @var string класс, на основе которого будут инициированы записи
	 */
	protected $_arClass = null;


	/**
	 * @param string $value
	 * @return \bx\ar\IFinder
	 */
	public function setArClass($value)
	{
		$this->_arClass = trim($value);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getArClass()
	{
		return $this->_arClass;
	}


	/**
	 * @param array $value
	 * @return \bx\ar\IFinder
	 */
	public function setOrder(array $value)
	{
		$this->_order = $value;
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
	 * @param array $value
	 * @return \bx\ar\IFinder
	 */
	public function setFilter(array $value)
	{
		$this->_filter = $value;
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
	 * @param int $value
	 * @return \bx\ar\IFinder
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
	 * @param int $value
	 * @return \bx\ar\IFinder
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
	 * @param int $time
	 */
	public function cache($time)
	{
		$this->_cache = (int) $time;
		return $this;
	}
}