<?php

namespace bx\ar\attributes;

/**
 * Списочное свойство инфоблока
 */
class IblockPropertyList extends IblockProperty
{
	/**
	 * @var array список вариантов для поля
	 */
	public static $_items = array();


	/**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$set = null;

		$ids = $this->getList('id');
		if (in_array($value, $ids)) $set = $value;

		foreach ($this->getList() as $item) {
			if ($value == $item['value'] || $value == $item['xml_id']) {
				$set = $item['id'];
				break;
			}
		}

		parent::setValue($set);
	}

	/**
	 * Возвращает xml_id значения
	 * @return string
	 */
	public function getXmlValue()
	{
		$list = $this->getList();
		$value = $this->getValue();
		return isset($list[$value]) ? $list[$value]['xml_id'] : null;
	}

	/**
	 * Возвращает человекопонятное значение списка
	 * @return string
	 */
	public function getHumanValue()
	{
		$list = $this->getList();
		$value = $this->getValue();
		return isset($list[$value]) ? $list[$value]['value'] : null;
	}

	/**
	 * Возвращает список вариантов для свойства
	 * @param string $getOnly
	 * @return array
	 */
	public function getList($getOnly = null)
	{
		$params = $this->getParams();
		if (!isset(self::$_items[$params['ID']]) && \CModule::IncludeModule('iblock')) {
			self::$_items[$params['ID']] = array();
			$res = \CIBlockPropertyEnum::GetList(
				array('SORT' => 'ASC', 'VALUE' => 'ASC'),
				array('PROPERTY_ID' => $params['ID'])
			);
			while ($ob = $res->Fetch()) {
				self::$_items[$params['ID']][$ob['ID']] = array(
					'id' => $ob['ID'],
					'value' => $ob['VALUE'],
					'xml_id' => $ob['XML_ID'],
					'def' => $ob['DEF'] == 'Y',
				);
			}			
		}

		if ($getOnly === null) {
			return self::$_items[$params['ID']];
		} else {
			$return = array();
			foreach (self::$_items[$params['ID']] as $item) {
				$return[] = isset($item[$getOnly]) ? $item[$getOnly] : null;
			}
			return $return;
		}
	}
}