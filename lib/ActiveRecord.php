<?php

namespace bx\ar;

use \bx\ar\iblock\Finder;

/**
 * Базовый класс для active record
 */
abstract class ActiveRecord implements \bx\ar\IActiveRecord
{
	/**
	 * @var array атрибуты
	 */
	protected $_attributes = null;
	/**
	 * @var array валидаторы
	 */
	protected $_validators = null;
	/**
	 * @var string сценарий для объекта
	 */
	protected $_scenario = 'default';


	/**
	 * Возвращает массив с описание атрибутов для данного типа записей
	 * @return array
	 */
	abstract protected function getAttributesDescriptions();

	/**
	 * Возвращает массив для валидации полей модели
	 * @return array
	 */
	abstract protected function rules();


	/**
	 * Создает объект для поиска нужных записей
	 * @param array $filter
	 * @param \bx\ar\IFinder $finder
	 * @return \bx\ar\IFinder
	 */
	public static function find(array $filter = null, \bx\ar\IFinder $finder = null)
	{
		$finder = $finder === null ? new Finder : $finder;
		$finder->setArClass(get_called_class());
		if (!empty($filter)) $finder->setFilter($filter);
		return $finder;
	}


	/**
	 * @param string $scenario
	 * @param array $value
	 */
	public function __construct($scenario = 'default', array $value = array())
	{
		$this->init($value);
		$this->setScenario($scenario);
	}


	/**
	 * Инициирует запись первоначальными значениями.
	 * В этой функции можно перехватить создание объекта записи и поправить его структуру
	 * @param array $value
	 */
	public function init(array $value)
	{
		$this->loadAttributes($value, true);
		$this->setValues($value);
		$this->validate(null, false);
	}

	/**
	 * Создает атрибуты Active Record
	 * @param array $init
	 */
	protected function loadAttributes(array $init, $force = false)
	{
		if ($this->_attributes == null || $force) {
			if ($force && !empty($this->_attributes)) {
				foreach ($this->_attributes as $key => $attr) {
					unset($attr);
				}
			}
			$atts = $this->getAttributesDescriptions();
			$this->_attributes = is_array($atts) ? $atts : array();
		}
	}

	/**
	 * Проверяет поля модели
	 * @param array $fields
	 * @param bool $setErrors
	 * @return bool
	 */
	public function validate(array $fields = null, $setErrors = true)
	{
		$this->initValidators();
		$return = true;
		foreach ($this->_validators as $validator) {
			$validate = $validator->validate($fields, $setErrors);
			$return = $return === false ? false : $validate;
		}
		return $return;
	}

	/**
	 * Инициирует все валидаторы модели
	 * @param bool $force
	 */
	protected function initValidators($force = false)
	{
		if ($this->_validators === null || $force) {
			$this->_validators = array();
			$rules = $this->rules();
			foreach ($rules as $rule) {
				$rule['model'] = $this;
				$this->_validators[] = \bx\ar\validators\Factory::create($rule);
			}
		}
	}


	/**
	 * Возвращает атрибут
	 * @param string $name
	 * @throw \bx\ar\Exception
	 * @return \bx\ar\IAttribute
	 */
	public function getAttribute($name)
	{
		$name = trim($name);
		if (isset($this->_attributes[$name])) {
			return $this->_attributes[$name];
		} else {
			throw new Exception('Request undefined attribute ' . $name);
		}
	}

	/**
	 * Задает значение атрибута
	 * @param string $name
	 * @param mixed $value
	 * @throw \bx\ar\Exception
	 */
	public function setValue($name, $value)
	{
		$attr = $this->getAttribute($name);
		if ($attr) {
			$attr->setValue($value);
		}
	}

	/**
	 * Возвращает значение атрибута
	 * @param string $name
	 * @throw \bx\ar\Exception
	 * @return mixed
	 */
	public function getValue($name)
	{
		$attr = $this->getAttribute($name);
		if ($attr) {
			return $attr->getValue();
		}
		return null;
	}

	/**
	 * Задает значения атрибутов из массива
	 * @param array $value
	 */
	public function setValues(array $values)
	{
		foreach ($values as $name => $value) {
			$this->setValue($name, $value);
		}
	}


	/**
	 * @param string $value
	 * @return \bx\ar\IActiveRecord
	 */
	public function setScenario($value)
	{
		$this->_scenario = trim($value);
	}

	/**
	 * @return string
	 */
	public function getScenario()
	{
		return $this->_scenario;
	}
}