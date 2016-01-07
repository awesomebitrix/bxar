<?php

namespace bxar\element;

/**
 * Класс для поиска элементов инфоблоков по запросу
 */
class Finder extends \bxar\Finder
{
	/**
	 * @var \marvin255\bxlib\IblockLocator
	 */
	protected $_iblockLocator = null;
	/**
	 * @var \CDBResult
	 */
	protected $_lastCDbResult = null;


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
			$finder->setArClass('\\bxar\\element\\Element');
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
		$select = Element::getBuiltFields();

		//собираем идентификаторы элементов и инфоблоков, чтобы запросить сразу все свойства
		$arIblocksAndElements = array();

		//запрос
		$rsElement = \CIBlockElement::GetList($order, 
			$filter, 
			false,
			!empty($nav) ? $nav : false,
			$select
		);
		$this->setLastCDBResult($rsElement);
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
			$iblockDescription = $this->getIblockDescription($iblockId);
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


	/**
	 * @param string $id
	 * @return array
	 */
	protected function getIblockDescription($id)
	{
		$locator = $this->getIblockLocator();
		if ($locator) {
			return $locator->findBy('ID', $id);
		} else {
			return \bxar\helpers\Iblock::getById($id);
		}
	}

	/**
	 * @return \marvin255\bxlib\IblockLocator
	 */
	public function getIblockLocator()
	{
		return $this->_iblockLocator;
	}

	/**
	 * @param \marvin255\bxlib\IblockLocator $locator
	 */
	public function setIblockLocator(\marvin255\bxlib\IblockLocator $locator)
	{
		$this->_iblockLocator = $locator;
		return $this;
	}

	/**
	 * @return \CDBResult
	 */
	public function getLastCDBResult()
	{
		return $this->_lastCDbResult;
	}

	/**
	 * @param \CDBResult $res
	 */
	protected function setLastCDBResult($res)
	{
		$this->_lastCDbResult = $res;
	}
}