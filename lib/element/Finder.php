<?php

namespace bx\ar\element;

/**
 * Класс для поиска элементов инфоблоков по запросу
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
			$finder->setArClass('\\bx\\ar\\element\\Element');
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

		return (int) \CIBlockElement::GetList(
			array(),
			$this->getFilter(),
			array(),
			false,
			array('ID')
		);
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
				$nav['bDescPageNumbering'] = false;
				$nav['bShowAll'] = false;
			} else {
				$nav['nTopCount'] = $this->getLimit();
			}
		}

		//поля для выборки
		$select = array(
			"ID",
			"IBLOCK_ID",
			"CODE",
			"XML_ID",
			"NAME",
			"ACTIVE",
			"DATE_ACTIVE_FROM",
			"DATE_ACTIVE_TO",
			"SORT",
			"PREVIEW_TEXT",
			"PREVIEW_TEXT_TYPE",
			"DETAIL_TEXT",
			"DETAIL_TEXT_TYPE",
			"DATE_CREATE",
			"CREATED_BY",
			"TIMESTAMP_X",
			"MODIFIED_BY",
			"TAGS",
			"IBLOCK_SECTION_ID",
			"DETAIL_PAGE_URL",
			"LIST_PAGE_URL",
			"DETAIL_PICTURE",
			"PREVIEW_PICTURE",
		);

		//собираем идентификаторы элементов и инфоблоков, чтобы запросить сразу все свойства
		$arIblocksAndElements = array();

		//запрос
		$rsElement = \CIBlockElement::GetList($order, 
			$filter, 
			false,
			!empty($nav) ? $nav : false,
			$select
		);
		while ($obElement = $rsElement->GetNext()) {
			$arItem = array();
			foreach ($obElement as $key => $value) {
				if (strpos($key, '~') !== 0) continue;
				$arItem[str_replace('~', '', $key)] = $value;
			}
			$arIblocksAndElements[$arItem['IBLOCK_ID']][] = $arItem['ID'];
			$return[] = $arItem;
		}

		//запрашиваем значения свойств инфоблоков
		foreach ($arIblocksAndElements as $iblockId => $ids) {
			$iblockDescription = \bx\ar\helpers\Iblock::getById($iblockId);
			//только если для инфоблока определены дополнительные свойства
			if (!empty($iblockDescription['PROPERTIES'])) {
				$filter = array('IBLOCK_ID' => $iblockId, 'ID' => array_unique($ids));
				$select = array('ID');
				$arProperties = array();
				foreach ($iblockDescription['PROPERTIES'] as $property) {
					$arProperties[$property['ID']] = $property;
					$select[] = 'PROPERTY_' . $property['ID'];
				}
				$rsElement = \CIBlockElement::GetList($order, 
					$filter, 
					false,
					false,
					$select
				);
				while ($obElement = $rsElement->GetNext()) {
					$itemProperties = array();
					foreach ($obElement as $key => $value) {
						if (!preg_match('/^PROPERTY_(\d+)_VALUE$/i', $key, $matches) || !isset($arProperties[$matches[1]])) continue;
						$property = $arProperties[$matches[1]];
						$code = !empty($property['CODE']) ? $property['CODE'] : $property['ID'];
						$value = $property['MULTIPLE'] === 'Y' && !is_array($value) ? array($value) : $value;
						if (!isset($itemProperties[$code])) {
							$itemProperties[$code] = $property;
							$itemProperties[$code]['VALUE'] = $value;
						} elseif ($property['MULTIPLE'] === 'Y') {
							$itemProperties[$code]['VALUE'] = array_merge($itemProperties[$code]['VALUE'], $value);
						}
					}
					foreach ($return as $itemKey => $item) {
						if ($item['ID'] === $obElement['ID']) {
							$return[$itemKey]['PROPERTIES'] = $itemProperties;
							break;
						}
					}
				}
			}
		}

		return $return;
	}
}