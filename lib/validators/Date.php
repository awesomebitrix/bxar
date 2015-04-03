<?php

namespace bx\ar\validators;

/**
 * Дата
 */
class Date extends \bx\ar\validators\Validator
{
	/**
	 * @param string
	 */
	public $toFormat = null;
	/**
	 * @param string
	 */
	public $message = 'Wrong date format';


	/**
	 * Валидирует конкретное значение поля
	 * @param string $name
	 * @return bool
	 */
	protected function validateAttribute(\bx\ar\IAttribute $attribute)
	{
		$value = $attribute->getValue();
		$timestamp = strtotime($value);
		if ($timestamp !== false) {
			if (strtoupper($this->toFormat) == 'FULL' || strtoupper($this->toFormat) == 'SHORT') {
				$attribute->setValue(\ConvertTimeStamp($timestamp, strtoupper($this->toFormat)));
			} elseif (!empty($this->toFormat)) {
				$attribute->setValue(\FormatDate($this->toFormat, $timestamp));				
			}
			return true;
		} else {
			$attribute->addError($this->message);
			return false;
		}
	}
}