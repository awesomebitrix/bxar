<?php

namespace bx\ar\attributes;

/**
 * Класс для связки элемента с разделами
 */
class BitrixSection extends Attribute
{
	/**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		if (is_array($value)) {
			$value = array_diff(array_map('intval', $value), array(0));
		} elseif (($id = intval($value)) !== 0) {
			$value = array($id);
		}
		parent::setValue($value);
	}

	/**
	 * Возвращает значение атрибута
	 * @return mixed
	 */
	public function getValue()
	{
		$value = parent::getValue();
		return $value === null ? $this->getSectionsId() : $value;
	}

	/**
	 * Возвращает значение для записи в базу данных
	 * @return mixed
	 */
	public function getValueToDb()
	{
		return null;
	}


	/**
	 * Запускает событие по атрибутам модели
	 * @param string $eventName
	 * @return bool
	 */
	public function riseEvent($eventName)
	{
		if ($eventName == 'afterSave') {
			$val = $this->getValue();
			if (!empty($val)) {
				$current = is_array($val) ? array_map('intval', $val) : array(intval($val));
			} else {
				$current = array();
			}
			sort($current);
			$inDb = $this->getSectionsId();
			if ($inDb !== $current) {
				if (!\CModule::IncludeModule('iblock')) throw new \bx\ar\Exception('Iblock module is not installed');
				\CIBlockElement::SetElementSection(
					$this->getModel()->getAttribute('id')->getValue(),
					!empty($current) ? $current : null
				);
			}
			$this->_sections = null;
		}
	}


	/**
	 * @var array
	 */
	protected static $_sections = null;

	/**
	 * Возвращает список всех разделов, к которым привязан элемент
	 * @return array
	 */
	protected function getSectionsId()
	{
		if (self::$_sections === null) {
			self::$_sections = array();
			if (!\CModule::IncludeModule('iblock')) throw new \bx\ar\Exception('Iblock module is not installed');
			$res = \CIBlockElement::GetElementGroups($this->getModel()->getAttribute('id')->getValue());
			while ($ob = $res->GetNext()) {
				self::$_sections[] = (int) $ob['ID'];
			}
			sort(self::$_sections);
		}
		return self::$_sections;
	}
}