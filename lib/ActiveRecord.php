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
	 * @param mixed $init
	 * @return array
	 */
	abstract protected function getAttributesDescriptions($init = null);

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
	public function __construct($scenario = 'default')
	{
		$this->setScenario($scenario);
	}


	/**
	 * Создает атрибуты Active Record
	 * @param array $init
	 */
	public function initAttributes(array $init, $force = false)
	{
		if ($this->_attributes == null || $force) {
			if ($force && !empty($this->_attributes)) {
				foreach ($this->_attributes as $key => $attr) {
					unset($attr);
				}
			}
			$atts = $this->getAttributesDescriptions($init);
			$this->_attributes = is_array($atts) ? $atts : array();
			foreach ($init as $code => $value) {
				if (isset($this->_attributes[$code]))
					$this->_attributes[$code]->setValue($value);
			}
			$this->validate(null, false);
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
	 * Возвращает массив с атрибутами модели
	 * @return array
	 */
	public function getAttributes()
	{
		return is_array($this->_attributes) ? $this->_attributes : array();
	}

	/**
	 * Возвращает атрибут модели по его имени
	 * @param string $name
	 * @return \bx\ar\IAttribute
	 */
	public function getAttribute($name)
	{
		$name = strtoupper($name);
		$attributes = $this->getAttributes();
		return isset($attributes[$name]) ? $attributes[$name] : null;
	}

	/**
	 * Задает значение атрибута модели
	 * @param string $name
	 * @param mixed $value
	 */
	public function setAttributeValue($name, $value)
	{
		$attr = $this->getAttribute($name);		
		if (isset($attr) && $this->isAttributeSafe($name)) {
			$attr->setValue($value);
		}
	}

	/**
	 * Задает значение атрибута модели
	 * @param string $name
	 */
	public function getAttributeValue($name)
	{
		$attr = $this->getAttribute($name);
		return isset($attr) ? $attr->getValue() : null;
	}

	/**
	 * Задает значения атрибутов из массива
	 * @param array $value
	 */
	public function setAttributesValues(array $value)
	{
		foreach ($value as $name => $val) {
			$this->setAttributeValue($name, $val);
		}
	}

	/**
	 * Возвращает значения всех атрибутов
	 * @return array
	 */
	public function getAttributesValues()
	{
		$return = array();
		$attributes = array_keys($this->getAttributes());
		foreach ($attributes as $name) {
			$return[$name] = $this->getAttributeValue($name);
		}
		return $return;
	}

	/**
	 * Возвращает список безопаных для записи атрибутов
	 * @return array
	 */
	protected function getSafeAttributes()
	{
		$rules = $this->rules();
		$scenario = $this->getScenario();
		$return = array();
		foreach ($rules as $rule) {
			if (empty($rule['on']) || $scenario == $rule['on'] || in_array($scenario, $rule['on'])) {
				$items = !is_array($rule[0]) ? array($rule[0]) : $rule[0]; 
				$return = array_merge($return, $items);
			}
		}
		return array_unique($return);
	}

	/**
	 * Проверяет возможно ли записать атрибут
	 * @param string $name
	 * @return bool
	 */
	protected function isAttributeSafe($name)
	{
		$name = strtoupper($name);
		$safe = $this->getSafeAttributes();		
		return in_array($name, $safe);
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