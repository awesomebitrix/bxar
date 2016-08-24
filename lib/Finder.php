<?php

namespace bxar;

/**
 * Базовый класс для поиска моделей по запросу
 */
abstract class Finder implements \bxar\IFinder
{
	/**
	 * @var array поля для выборки
	 */
	protected $_select = array();
	/**
	 * @var array сортиовка
	 */
	protected $_order = array();
	/**
	 * @var array условия для выборки
	 */
	protected $_filter = array();
	/**
	 * @var int ограничение количества элементов
	 */
	protected $_limit = null;
	/**
	 * @var int смещение для выборки элементов
	 */
	protected $_offset = null;
	/**
	 * @var string класс, на основе которого будут инициированы записи
	 */
	protected $_arClass = null;
	/**
	 * @var bool вернуть модель ar или массив
	 */
	protected $_asArray = false;
	/**
	 * @var string поле, которое будет использовано в качестве индекса в результирующем массиве
	 */
	protected $_index = null;


	/**
	 * @param string $value
	 * @return \bxar\IFinder
	 */
	public function setArClass($value)
	{
		$this->_arClass = $value;
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
	 * @return \bxar\IFinder
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
	 * @param array $value
	 * @return \bxar\IFinder
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
	 * @return \bxar\IFinder
	 */
	public function setFilter(array $value)
	{
		$this->_filter = $value;
		return $this;
	}

	/**
	 * @param array $value
	 * @return \bxar\IFinder
	 */
	public function mergeFilterWith(array $value)
	{
		$this->_filter = array_merge($this->_filter, $value);
		return $this;
	}

	/**
	 * @param array $value
	 * @return \bxar\IFinder
	 */
	public function andFilter(array $value)
	{
		$current = $this->getFilter();
		$this->_filter = [
			'LOGIC' => 'AND',
			$current,
			$value
		];
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
	 * @return \bxar\IFinder
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
	 * @return \bxar\IFinder
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
	 * @param int $value
	 * @return \bxar\IFinder
	 */
	public function setAsArray($value = true)
	{
		$this->_asArray = (bool) $value;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getAsArray()
	{
		return $this->_asArray;
	}


	/**
	 * @param string $value
	 * @return \bxar\IFinder
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
	 * Инициирует модель ar
	 * @param array $init
	 * @return \bxar\IActiveRecord
	 */
	protected function initItem(array $init)
	{
		$class = $this->getArClass();
		if (is_callable($class)) {
			$item = $class();
		} else {
			$item = new $class;
		}
		$item->initAttributes($init);
		return $item;
	}
}
