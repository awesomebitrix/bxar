<?php

namespace bx\ar\validators;

/**
 * Битриксовый булевый формат
 */
class BxBool extends \bx\ar\validators\Validator
{
	/**
	 * Валидирует конкретное значение поля
	 * @param string $name
	 * @return bool
	 */
	protected function validateAttribute(\bx\ar\IAttribute $attribute, $setErrors = true)
	{
		$value = $attribute->getValue();
		$value = $value !== 'N' ? ($value ? 'Y' : 'N') : 'N';
		$attribute->setValue($value);
		return true;
	}
}