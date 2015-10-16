<?php

namespace bx\ar\section;

/**
 * Класс для поиска разделов инфоблоков по запросу
 */
class Finder extends \bx\ar\Finder
{
	/**
	 * @param array $filter
	 * @param string $arClass
	 * @return \bx\ar\IFinder
	 */
	public static function find(array $filter = null, $arClass = null)
	{
		$finder = new self;
		if ($filter !== null) $finder->setFilter($filter);
		if ($arClass === null) {
			$finder->setArClass('\\bx\\ar\\section\\Section');
		} else {
			$finder->setArClass($arClass);
		}
		return $finder;
	}


	/**
	 * Находит один элемент
	 * @return \bx\ar\IActiveRecord
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
		if (!\CModule::IncludeModule('iblock')) return 0;

		$count = 0;
		$res = \CIBlockSection::GetList(
			array(),
			$this->getFilter(),
			false,
			array('ID')
		);
		while ($ob = $res->Fetch()) {
			$count++;
		}

		return $count;
	}


	/**
	 * @var bool
	 */
	protected $_bIncCnt = false;

	/**
	 * Задает флаг, считать количество вложенных элементов или нет
	 * @param bool $value
	 * @return \bx\ar\section\Finder
	 */
	public function setBIncCnt($value = true)
	{
		$this->_bIncCnt = (bool) $value;
	}

	/**
	 * Возвращает флаг, считать количество вложенных элементов или нет
	 * @return bool
	 */
	public function getBIncCnt()
	{
		return $this->_bIncCnt;
	}


	/**
	 * Запрашиваем данные из базы
	 * @return array
	 */
	protected function getList()
	{
		$return = array();

		if (!\CModule::IncludeModule('iblock')) return $return;

		//сортировка
		$order = $this->getOrder();

		//условия для поиска
		$filter = $this->getFilter();

		//ограничение количества и смещение
		$nav = array();
		if ($this->getLimit()) {
			if ($this->getOffset() !== null) {
				$nav['nPageSize'] = $this->getLimit();
				$nav['iNumPage'] = ceil($this->getOffset() / $this->getLimit()) + 1;
				$nav['bShowAll'] = false;
			} else {
				$nav['nTopCount'] = $this->getLimit();
			}
		}

		//поля для выборки
		$select = array(
			'ID',
			'CODE',
			'XML_ID',
			'IBLOCK_ID',
			'IBLOCK_SECTION_ID',
			'SORT',
			'NAME',
			'ACTIVE',
			'GLOBAL_ACTIVE',
			'PICTURE',
			'DESCRIPTION',
			'DESCRIPTION_TYPE',
			'LEFT_MARGIN',
			'RIGHT_MARGIN',
			'DEPTH_LEVEL',
			'SECTION_PAGE_URL',
			'MODIFIED_BY',
			'DATE_CREATE',
			'CREATED_BY',
			'DETAIL_PICTURE',
		);

		//запрос
		$rsElement = \CIBlockSection::GetList(
			$order, 
			$filter, 
			$this->getBIncCnt(),
			$select,
			!empty($nav) ? $nav : false
		);
		while ($obElement = $rsElement->GetNext()) {
			$arItem = array();
			foreach ($obElement as $key => $value) {
				if (strpos($key, '~') !== 0) continue;
				$arItem[str_replace('~', '', $key)] = $value;
			}
			$return[] = $arItem;
		}

		return $return;
	}
}