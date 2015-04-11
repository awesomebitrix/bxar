<?php

namespace bx\ar\validators;

/**
 * Проверяет находится ли значение среди заданных
 */
class In extends \bx\ar\validators\Validator
{
	/**
	 * @var array список допустимых значений
	 */
	public $range = array();
	/**
	 * @var string сообщение об ошибке
	 */
	public $message = 'Wrong value';
	/**
	 * @var bool строгая проверка
	 */
	public $strict = false;
	/**
	 * @var bool не выводить ошибку, если пустое
	 */
	public $allowEmpty = true;


	/**
	 * Валидирует конкретное значение поля
	 * @param string $name
	 * @return bool
	 */
	protected function validateAttribute(\bx\ar\IAttribute $attribute, $setErrors = true)
	{
		$value = $attribute->getValue();
		$res = false;
		foreach ($this->range as $val) {
			if (($this->strict && $val === $value) || (!$this->strict && $val == $value)) {
				$res = true;
				break;
			}
		}
		if ($res || ($this->allowEmpty && $value === null)) {
			return true;
		} else {
			$attribute->addError($this->message);
			return false;
		}
	}
}