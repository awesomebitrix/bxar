<?php

namespace bx\ar\attributes;

/**
 * Числовое свойство инфоблока
 */
class IblockPropertyNumber extends IblockProperty
{	
	/**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$this->_value = (float) $value;
	}
}