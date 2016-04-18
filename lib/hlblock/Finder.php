<?php

namespace bxar\hlblock;

/**
 * Класс для поиска элементов hlblock по запросу
 */
class Finder extends \bxar\Finder
{
	/**
	 * @var string
	 */
	protected $_entity = null;
	/**
	 * @var array
	 */
	protected $_runtime = [];


	/**
	 * @param array $filter
	 * @param string $arClass
	 * @return \bxar\IFinder
	 */
	public static function find(array $filter = null, $arClass = null)
	{
		$finder = new self;
		if ($filter !== null) $finder->setFilter($filter);
		if ($arClass === null) {
			$finder->setArClass('\\bxar\\hlblock\\Element');
		} else {
			$finder->setArClass($arClass);
		}
		return $finder;
	}


	/**
	 * Находит один элемент
	 * @return \bxar\IActiveRecord
	 */
	public function one()
	{
		$this->setLimit(1);
		$res = $this->getList();
		if (!empty($res)) {
			$arInit = reset($res);
			return $this->getAsArray() ? $arInit : $this->initItem($arInit);
		} else {
			return null;
		}
	}

	/**
	 * Находит несколько элементов
	 * @return array
	 */
	public function all()
	{
		$return = array();
		$res = $this->getList();
		if (!empty($res)) {
			foreach ($res as $baseKey => $arInit) {
				$index = $this->getIndex();
				if ($this->getAsArray()) {
					$key = $index !== null ? trim($arInit[$index]) : $baseKey;
					$return[$key] = $arInit;
				} else {
					$arItem = $this->initItem($arInit);
					$key = $index !== null ? $arItem->getAttribute($index)->getValue() : $baseKey;
					$return[$key] = $arItem;
				}
			}
		}
		return $return;
	}

	/**
	 * Находит количество элементов по запросу
	 * @return int
	 */
	public function count()
	{
		$class = \bxar\helpers\HlEntity::compile($this->getEntity());
		if (!$class) throw new Exception('Entity does not set.');

		$arQuery = [
			'select' => array('CNT'),
			'runtime' => array(
				new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(*)')
			),
		];

		//условия для поиска
		$filter = $this->getFilter();
		if ($filter) $arQuery['filter'] = $filter;

		//runtime
		$runtime = $this->getRuntime();
		if ($runtime) {
			foreach ($runtime as $rt) {
				$arQuery['runtime'][] = new \Bitrix\Main\Entity\ExpressionField($rt[0], $rt[1]);
			}
		}

		$res = $class::getList($arQuery)->fetch();
		return isset($res['CNT']) ? (int) $res['CNT'] : 0;
	}


	/**
	 * Запрашиваем данные из базы
	 * @return array
	 */
	protected function getList()
	{
		$class = \bxar\helpers\HlEntity::compile($this->getEntity());
		if (!$class) throw new Exception('Entity does not set.');

		$return = array();
		$arQuery = [];

		//сортировка
		$order = $this->getOrder();
		if ($order) $arQuery['order'] = $order;

		//условия для поиска
		$filter = $this->getFilter();
		if ($filter) $arQuery['filter'] = $filter;

		//ограничение количества
		$limit = (int) $this->getLimit();
		if ($limit) $arQuery['limit'] = $limit;

		//смещение
		$offset = (int) $this->getOffset();
		if ($offset) $arQuery['offset'] = $offset;

		//runtime
		$runtime = $this->getRuntime();
		if ($runtime) {
			foreach ($runtime as $rt) {
				$arQuery['runtime'][] = new \Bitrix\Main\Entity\ExpressionField($rt[0], $rt[1]);
			}
		}

		return $class::getList($arQuery)->fetchAll();
	}


	/**
	 * @param array runtime
	 * @return \bxar\hlblock\Finder
	 */
	public function setRuntime(array $rt)
	{
		$this->_runtime = $rt;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getRuntime()
	{
		return $this->_runtime;
	}

	/**
	 * @param array runtime
	 * @return \bxar\hlblock\Finder
	 */
	public function mergeRuntimeWith(array $rt)
	{
		$rtOld = $this->getRuntime();
		$this->setRuntime($rtOld ? array_merge($rtOld, $rt) : $rt);
		return $this;
	}


	/**
	 * Задает сущность для поиска
	 * @param string $entity
	 * @return \bxar\hlblock\Finder
	 */
	public function setEntity($entity)
	{
		$this->_entity = trim($entity);
		return $this;
	}

	/**
	 * Возвращает сущность для поиска
	 * @return string
	 */
	public function getEntity()
	{
		return $this->_entity;
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
		$item->setEntity($this->getEntity());
		$item->initAttributes($init);
		return $item;
	}
}
