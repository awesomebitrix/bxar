<?php

namespace bx\ar\validators;

/**
 * Валидатор с фильтром
 */
class Filter extends \bx\ar\validators\Validator
{
	/**
	 * @param string
	 */
	public $filter = null;


	/**
	 * Валидирует конкретное значение поля
	 * @param string $name
	 * @return bool
	 */
	protected function validateAttribute(\bx\ar\IAttribute $attribute)
	{
		if (($filter = trim($this->filter)) !== '') {
			$value = call_user_func($filter, $attribute->getValue());
			$attribute->setValue($value);
		}
		return true;
	}
}