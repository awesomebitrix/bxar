<?php

namespace bxar\attributes;

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
		
		/* fix add array condition at 28.09.2016 */
		if (!empty($value) && is_array($value)) {
    	$value = reset($value);
    }
		/* fix end */

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
