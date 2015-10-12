<?php

namespace bx\ar\validators;

/**
 * Валидатор, который устанавливает значения по умолчанию
 */
class DefaultValue extends \bx\ar\validators\Validator
{
	/**
	 * @param string
	 */
	public $default = null;
	/**
	 * @param string
	 */
	public $defaultArray = null;


	/**
	 * Валидирует конкретное значение поля
	 * @param string $name
	 * @return bool
	 */
	protected function validateAttribute(\bx\ar\IAttribute $attribute, $setErrors = true)
	{
		$new = $this->getModel()->isNew();
		if ($new) {
			$attrValue = $attribute->getValue();
			if (is_array($this->defaultArray)) {
				$code = $attribute->getCode();
				if (isset($this->defaultArray[$code])) {
					$attribute->setValue($this->defaultArray[$code]);
				}
			} else {
				if ($attrValue === null || $attrValue === '') {
					$attribute->setValue($default);
				}
			}
		}
		return true;
	}
}