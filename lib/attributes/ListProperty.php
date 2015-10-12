<?php

namespace bx\ar\attributes;

/**
 * Класс для свойства со списком
 */
class ListProperty extends Attribute
{
	/**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$toSet = null;
		if ($value !== null && $value !== '') {
			$list = $this->getListItems();
			//перебираем все id
			foreach ($list as $item) {
				if ($item['ID'] == $value) {
					$toSet = $item['ID'];
					break;
				}
			}
			//перебираем все xml_id
			if ($toSet !== null) {
				foreach ($list as $item) {
					if ($item['XML_ID'] == $value) {
						$toSet = $item['ID'];
						break;
					}
				}
			}
		}
		parent::setValue($toSet);
	}

	/**
	 * Возвращает xml_id для значения
	 * @return string
	 */
	public function getXmlId()
	{
		$return = null;
		$value = $this->getValue();
		if ($value) {
			$list = $this->getListItems();
			foreach ($list as $item) {
				if ($item['ID'] == $value) {
					$return = $item['XML_ID'];
					break;
				}
			}
		}
		return $return;
	}

	/**
	 * Возвращает читаемое значение для значения
	 * @return string
	 */
	public function getReadable()
	{
		$return = null;
		$value = $this->getValue();
		if ($value) {
			$list = $this->getListItems();
			foreach ($list as $item) {
				if ($item['ID'] == $value) {
					$return = $item['VALUE'];
					break;
				}
			}
		}
		return $return;
	}

	/**
	 * Возвращает все значения списка для указанного свойства
	 * @return array
	 */
	public function getListItems()
	{
		$return = array();
		$id = (int) $this->getParam('id');
		if ($id > 0) {
			$return = \bx\ar\helpers\Enum::getById($id);
		}
		return $return;
	}
}