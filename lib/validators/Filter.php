<?php

namespace bxar\validators;

/**
 * Валидатор с фильтром
 */
class Filter extends \bxar\validators\Validator
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
	protected function validateAttribute(\bxar\IAttribute $attribute, $setErrors = true)
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