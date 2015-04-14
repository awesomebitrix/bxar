<?php

namespace bx\ar\validators;

/**
 * Без валидации
 */
class Safe extends \bx\ar\validators\Validator
{
	/**
	 * Валидирует конкретное значение поля
	 * @param string $name
	 * @return bool
	 */
	protected function validateAttribute(\bx\ar\IAttribute $attribute, $setErrors = true)
	{
		return true;
	}
}