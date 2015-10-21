<?php

namespace bxar\attributes;

/**
 * Класс для числового свойства
 */
class Numeric extends Attribute
{
	/**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$value = (float) str_replace(array(' ', ','), array('', '.'), $value);
		parent::setValue($value);
	}
}