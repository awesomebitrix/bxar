<?php

namespace bxar\user;

/**
 * Класс для поиска пользователей по запросу
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
			$finder->setArClass('\\bxar\\user\\User');
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
		$res = \CUser::GetList(
			$this->getOrder(),
			($a = null),
			$this->getFilter(),
			array('NAV_PARAMS' => array('nPageSize' => 1, 'bDescPageNumbering' => 'N', 'bShowAll' => 'N'))
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

		//условия для поиска
		$filter = $this->getFilter();

		//параметры для юзеров
		$params = array('SELECT' => $this->getUserFields());

		//ограничение количества и смещение
		$nav = array();
		if ($this->getLimit()) {
			if ($this->getOffset() !== null) {
				$nav['nPageSize'] = $this->getLimit();
				$nav['iNumPage'] = ceil($this->getOffset() / $this->getLimit()) + 1;
				$nav['bDescPageNumbering'] = false;
				$nav['bShowAll'] = false;
			} else {
				$nav['nTopCount'] = $this->getLimit();
			}
			$params['NAV_PARAMS'] = $nav;
		}

		//запрос
		$rsElement = \CUser::GetList(
			$order,
			($a = null),
			$filter,
			$params
		);
		while ($ob = $rsElement->Fetch()) {
			$return[] = $ob;
		}

		return $return;
	}


	/**
	 * Возвращает список пользовательских полей
	 * @return array
	 */
	protected function getUserFields()
	{
		$select = \bxar\user\User::getBuiltFields();
		$description = \bxar\helpers\Uf::getListFor('USER');
		foreach ($description as $descr) {
			$select[] = $descr['FIELD_NAME'];
		}
		return $select;
	}
}
