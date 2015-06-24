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
	protected function validateAttribute(\bx\ar\IAttribute $attribute, $setErrors = true)
	{
		if (($filter = trim($this->filter)) !== '') {
			$attrValue = $attribute->getValue();
			if (is_array($attrValue)) {
				$value = array_map($filter, $attrValue);
			} else {
				$value = call_user_func($filter, $attrValue);
			}
			$attribute->setValue($value);
		}
		return true;
	}
}