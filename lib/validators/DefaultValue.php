<?php

namespace bx\ar\validators;

/**
 * Значение по умолчанию
 */
class DefaultValue extends \bx\ar\validators\Validator
{
	/**
	 * @param string
	 */
	public $value = null;


	/**
	 * Валидирует конкретное значение поля
	 * @param string $name
	 * @return bool
	 */
	protected function validateAttribute(\bx\ar\IAttribute $attribute)
	{
		$value = $attribute->getValue();
		if ($value === null) $attribute->setValue($this->value);
		return true;
	}
}