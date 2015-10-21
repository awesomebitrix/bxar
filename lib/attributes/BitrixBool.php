<?php

namespace bxar\attributes;

/**
 * Класс для свойства типа bool из битрикса
 */
class BitrixBool extends Attribute
{
	/**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$value = $value && $value !== 'N' ? 'Y' : 'N';
		parent::setValue($value);
	}

	/**
	 * Возвращает значение типа bool
	 * @return bool
	 */
	public function getValueBool()
	{
		$value = $this->getValue();
		return $value === 'Y' ? true : false;
	}
}