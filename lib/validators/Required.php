<?php

namespace bx\ar\validators;

/**
 * Обязательный атрибут
 */
class Required extends \bx\ar\validators\Validator
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
	protected function validateAttribute(\bx\ar\IAttribute $attribute)
	{
		$value = $attribute->getValue();
		if ($value === null || $value === '') {
			$attribute->addError($this->message);
			return false;
		} else {
			return true;
		}
	}
}