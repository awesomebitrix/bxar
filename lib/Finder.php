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
	 * @var bool вернуть модель ar или массив
	 */
	protected $_asArray = false;


	/**
	 * @return string
	 */
	protected function getCacheId()
	{
		return json_encode($this->getArClass() . $this->getLimit() . $this->getOffset())
		       . json_encode($this->getFilter())
		       . json_encode($this->getOrder());
	}

	/**
	 * @param string $cid
	 * @return mixed
	 */
	protected function getFromCache($cid = null)
	{
		$cid = $cid === null ? $this->getCacheId() : $cid;
		$cTime = $this->_cache;
		if (!$cTime) return false;
		$obCache = new \CPHPCache();
		if ($obCache->InitCache($cTime, $cid)) {
			return $obCache->GetVars();
		} else {
			return false;
		}
	}

	/**
	 * @param mixed $value
	 * @param string $cid
	 */
	protected function setToCache($value, $cid = null)
	{
		$cid = $cid === null ? $this->getCacheId() : $cid;
		$cTime = $this->_cache;
		if (!$cTime) {
			return null;
		} else {
			$obCache = new \CPHPCache();
			$obCache->InitCache($cTime, $cid);
			$obCache->StartDataCache();
			$obCache->EndDataCache($value);
		}
	}


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
	 * @param int $value
	 * @return \bx\ar\IFinder
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
	 * @param int $time
	 */
	public function cache($time)
	{
		$this->_cache = (int) $time;
		return $this;
	}
}