<?php

namespace bxar\validators;

/**
 * Без валидации
 */
class Safe extends \bxar\validators\Validator
{
	/**
	 * Валидирует конкретное значение поля
	 * @param string $name
	 * @return bool
	 */
	protected function validateAttribute(\bxar\IAttribute $attribute, $setErrors = true)
	{
		return true;
	}
}