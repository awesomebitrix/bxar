<?php

namespace bxar\validators;

/**
 * Базовый класс для валидаторов
 */
abstract class Validator implements \bxar\IValidator
{
	/**
	 * @var \bxar\IActiveRecord
	 */
	protected $_model = null;
	/**
	 * @var array
	 */
	protected $_attributes = null;
	/**
	 * @var array
	 */
	protected $_on = null;


	/**
	 * Валидирует конкретное значение поля
	 * @param string $name
	 * @param bool $setErrors
	 * @return bool
	 */
	abstract protected function validateAttribute(\bxar\IAttribute $attribute, $setErrors = true);


	/**
	 * @param array $fields
	 * @param bool $setErrors
	 * @return bool
	 */
	public function validate(array $fields = null, $setErrors = true)
	{
		$return = true;
		$scenario = $this->getModel()->getScenario();
		$scenarios = $this->getOn();
		if (empty($scenarios) || in_array($scenario, $scenarios)) {
			foreach ($this->getAttributes() as $attr) {
				if ($fields !== null && !in_array($attr, $fields)) continue;
				$attribute = $this->getModel()->getAttribute($attr);
				$validate = $this->validateAttribute($attribute, $setErrors);
				$return = $return === false ? false : $validate;
			}
		}
		return $return;
	}


	/**
	 * @param $value
	 */
	public function setOn(array $value)
	{
		$this->_on = is_array($value) ? $value : array($value);
	}

	/**
	 * @return array
	 */
	public function getOn()
	{
		return $this->_on;
	}


	/**
	 * @param array $value
	 */
	public function setAttributes($value)
	{
		$this->_attributes = is_array($value) ? $value : array($value);
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->_attributes;
	}


	/**
	 * @param \bxar\IActiveRecord $value
	 */
	public function setModel(\bxar\IActiveRecord $value)
	{
		$this->_model = $value;
	}

	/**
	 * @return \bxar\IActiveRecord|null
	 */
	public function getModel()
	{
		return $this->_model;
	}
}