<?php

namespace bxar\validators;

/**
 * Обязательный атрибут
 */
class Required extends \bxar\validators\Validator
{
	/**
	 * @param string
	 */
	public $message = 'This attribute can\'t be empty';


	/**
	 * Валидирует конкретное значение поля
	 * @param string $name
	 * @return bool
	 */
	protected function validateAttribute(\bxar\IAttribute $attribute, $setErrors = true)
	{
		$value = $attribute->getValue();
		if ($value === null || $value === '') {
			if ($setErrors) $attribute->addError($this->message);
			return false;
		} else {
			return true;
		}
	}
}