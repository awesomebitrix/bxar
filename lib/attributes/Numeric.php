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
		parent::setValue($value);
		$val = $this->getValue();
		$val = (float) str_replace(array(' ', ','), array('', '.'), $val);
		parent::setValue($val);
	}
}
