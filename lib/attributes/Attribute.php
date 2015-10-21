<?php

namespace bxar\attributes;

/**
 * Базовый класс для атрибутов
 */
class Attribute implements \bxar\IAttribute
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
	 * @var \bxar\IActiveRecord родительская модель
	 */
	protected $_model = null;



	/**
	 * Магия для быстрого доступа к данным свойства
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		$getter = 'get' . ucfirst($name);
		if (method_exists($this, $getter)) {
			return $this->$getter();
		} else {
			return null;
		}
	}

	/**
	 * Магия для быстрого доступа к данным свойства
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		$setter = 'set' . ucfirst($name);
		if (method_exists($this, $setter)) {
			return $this->$setter($value);
		} else {
			return null;
		}
	}



	/**
	 * Задает настройки поля
	 * @param array $options
	 */
	public function initAttributes(array $options)
	{
		if (isset($options['model'])) $this->setModel($options['model']);
		if (isset($options['code'])) $this->setCode($options['code']);
		if (isset($options['params'])) $this->setParams($options['params']);
		if (isset($options['value'])) $this->setValue($options['value']);
	}



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
		return $this->getValue();
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
	 * Возвращает значение указанного параметра
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function getParam($name, $default = null)
	{
		$name = strtoupper($name);
		return isset($this->_params[$name]) ? $this->_params[$name] : $default;
	}



	/**
	 * Задает значение атрибута
	 * @param \bxar\IActiveRecord $model
	 */
	public function setModel(\bxar\IActiveRecord $model)
	{
		$this->_model = $model;
	}

	/**
	 * Возвращает значение атрибута
	 * @return \bxar\IActiveRecord
	 */
	public function getModel()
	{
		return $this->_model;
	}


	/**
	 * Запускает событие по атрибутам модели
	 * @param string $eventName
	 * @return bool
	 */
	public function riseEvent($eventName)
	{
		return true;
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