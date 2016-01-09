<?php

namespace bxar\group;

/**
 * Класс для поиска групп пользователей по запросу
 */
class Finder extends \bxar\Finder
{
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
			$finder->setArClass('\\bxar\\group\\Group');
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
					$key = $index !== null ? $arItem->getAttributeValue($index) : $baseKey;
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
		$res = \CGroup::GetList(
			($by = 'c_sort'),
			($order = 'desc'),
			$this->getFilter()
		);

		return (int) $res->NavRecordCount;
	}


	/**
	 * Запрашиваем данные из базы
	 * @return array
	 */
	protected function getList()
	{
		$return = array();

		//сортировка
		$order = $this->getOrder();
		$keys = array_keys($order);
		$by = reset($keys);
		$order = reset($order);

		//условия для поиска
		$filter = $this->getFilter();

		//запрос
		$rsElement = \CGroup::GetList(
			$by,
			$order,
			$filter
		);
		while ($ob = $rsElement->Fetch()) {
			$return[] = $ob;
		}

		return $return;
	}
}