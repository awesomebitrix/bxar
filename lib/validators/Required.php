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
	public $message = 'Поле #label# должно быть заполнено';


	/**
	 * Валидирует конкретное значение поля
	 * @param string $name
	 * @return bool
	 */
	protected function validateAttribute(\bxar\IAttribute $attribute, $setErrors = true)
	{
		$value = $attribute->getValue();
		if ($value === null || $value === '') {
			$params = $attribute->getParams();
			if ($setErrors) $attribute->addError(str_replace(
				[
					'#label#'
				],
				[
					!empty($params['NAME']) ? $params['NAME'] : '',
				],
				$this->message
			));
			return false;
		} else {
			return true;
		}
	}
}
