<?php

namespace bxar\validators;

/**
 * Валидатор для email
 */
class Email extends \bxar\validators\Validator
{
		/**
	 * @param string
	 */
	public $message = 'Wrong email format';


	/**
	 * Валидирует конкретное значение поля
	 * @param string $name
	 * @return bool
	 */
	protected function validateAttribute(\bxar\IAttribute $attribute, $setErrors = true)
	{
		$value = $attribute->getValue();
		if (!empty($value) && !((bool) preg_match('/^[^@]+@[0-9\-a-zA-Z_]+\.[a-zA-Z]{2,6}$/', $value))) {
			if ($setErrors) $attribute->addError($this->message);
			return false;
		} else {
			return true;
		}
	}
}