<?php

namespace bx\ar\attributes;

/**
 * Класс для числового свойства
 */
class Date extends Attribute
{
	/**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		if (!empty($value) && ($time = strtotime($value)) !== false) {
			$value = ConvertTimeStamp($time, 'FULL');
		} else {
			$value = null;
		}
		parent::setValue($value);
	}

	/**
	 * Возвращает дату отформатированную согласно параметру
	 * @param string $format
	 * @return string
	 */
	public function getValueFormatted($format = 'd.m.Y')
	{
		$value = $this->getValue();
		if (!empty($value) && ($time = strtotime($value)) !== false) {
			$value = \FormatDate($format, $time);
		} else {
			$value = '';
		}
		return $value;
	}
}