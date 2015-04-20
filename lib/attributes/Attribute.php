<?php

namespace bx\ar\attributes;

/**
 * Базовый класс для атрибутов
 */
class Attribute implements \bx\ar\IAttribute
{
	/**
	 * @var mixed значение атрибута
	 */
	protected $_value = null;
	/**
	 * @var mixed код атрибута, по которому к нему можно будет обратиться
	 */
	protected $_code = null;
	/**
	 * @var mixed параметры атрибута
	 */
	protected $_params = null;
	/**
	 * @var mixed ошибки для атрибута
	 */
	protected $_errors = null;
	/**
	 * @var \bx\ar\IActiveRecord родительская модель
	 */
	protected $_model = null;


	/**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$this->_value = $value;
	}

	/**
	 * Возвращает значение атрибута
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/**
	 * Возвращает значение для записи в базу данных
	 * @return mixed
	 */
	public function getValueToDb()
	{
		$params = $this->getParams();
		if (array_key_exists('DESCRIPTION', $params)) {
			return array(
				'VALUE' => $this->getValue(),
				'DESCRIPTION' => $params['DESCRIPTION'],
			);
		} else {
			return $this->getValue();
		}		
	}


	/**
	 * Задает код атрибута
	 * @param string $value
	 */
	public function setCode($value)
	{
		$value = trim($value);
		$this->_code = $value == '' ? null : $value;
	}

	/**
	 * Возвращает код атрибута
	 * @return mixed
	 */
	public function getCode()
	{
		return $this->_code;
	}


	/**
	 * Задает параметры атрибута
	 * @param array $value
	 */
	public function setParams(array $value = null)
	{
		if (!empty($value))
			$this->_params = $value;
	}

	/**
	 * Возвращает параметры атрибута
	 * @return array
	 */
	public function getParams()
	{
		return $this->_params;
	}


	/**
	 * Возвращает список ошибок для атрибута
	 * @return array
	 */
	public function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * Задает список ошибок для атрибута
	 * @param array $value
	 */
	public function setErrors(array $value = array())
	{
		$this->_errors = array();
		foreach ($value as $error) {
			$this->addError($error);
		}
	}

	/**
	 * Добавляет ошибку в список для атрибута
	 * @param string $value
	 */
	public function addError($value)
	{
		$this->_errors[] = trim($value);
	}

	/**
	 * Проверяет есть ли ошибки у атрибута
	 * @param string $value
	 */
	public function hasErrors()
	{
		return !empty($this->_errors);
	}
}