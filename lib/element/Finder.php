<?php

namespace bx\ar\element;

/**
 * Класс для поиска элементов инфоблоков по запросу
 */
class Finder extends \bx\ar\Finder
{
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
	 * Подключаем стандартный битриксовый апи для поиска
	 */
	protected function getList()
	{
		$return = array();

		if (!\CModule::IncludeModule('iblock')) return $return;

		$order = $this->getOrder();
		$filter = $this->getFilter();
		$nav = array();
		if ($this->getOffset()) {
			$nav['iNumPage'] = $this->getOffset();
		}
		if ($this->getLimit()) {
			$nav['nPageSize'] = $this->getLimit();
		}

		$rsElement = \CIBlockElement::GetList(
			$order, 
			$filter, 
			false,
			!empty($nav) ? $nav : false,
			array(
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
				"PROPERTY_*",
			)
		);
		while ($obElement = $rsElement->GetNextElement()) {
			$arItem = $obElement->GetFields();
			$arItem["PROPERTIES"] = $obElement->GetProperties();
			$return[] = $arItem;
		}

		return $return;
	}
}