<?php

namespace bxar;

/**
 * Базовый класс для active record
 */
abstract class ActiveRecord implements \bxar\IActiveRecord
{
	/**
	 * @var array валидаторы
	 */
	protected $_validators = null;
	/**
	 * @var array
	 */
	protected $_attributes = array();
	/**
	 * @var string
	 */
	protected $_scenario = 'default';



	/**
	 * Создает атрибуты Active Record
	 * @param array $init
	 */
	abstract public function initAttributes(array $init);

	/**
	 * Возвращает массив для валидации полей модели
	 * @return array
	 */
	abstract protected function rules();

	/**
	 * Удаляет запись
	 * @return bool
	 */
	abstract public function delete();

	/**
	 * Сохраняет запись
	 * @return bool
	 */
	abstract public function save();

	/**
	 * Есть ли данна запись в базе данных
	 * @return bool
	 */
	abstract public function isNew();



	/**
	 * Магия для быстрого доступа к атрибутам
	 * @param string $name
	 * @return null|\bxar\IAttribute
	 */
	public function __get($name)
	{
		return $this->getAttribute($name);
	}

	/**
	 * Магия для быстрой проверки существует ли указанный атрибут модели
	 * @param string $name
	 * @return bool
	 */
	public function __isset($name)
	{
		return $this->issetAttribute($name);
	}

	/**
	 * @param array $value
	 * @param string $scenario
	 */
	public function __construct(array $value = null, $scenario = 'default')
	{
		$this->setScenario($scenario);
		if ($value) $this->initAttributes($value);
	}



	/**
	 * Проверяет поля модели
	 * @param array $fields
	 * @param bool $setErrors
	 * @return bool
	 */
	public function validate(array $fields = null, $setErrors = true)
	{
		$return = true;
		foreach ($this->getValidators($fields) as $validator) {
			$validate = $validator->validate($fields, $setErrors);
			$return = $return === false ? false : $validate;
		}
		return $return;
	}

	/**
	 * Инициирует все валидаторы модели
	 * валидаторы инциируем "по запросу"
	 * @param array $fields
	 * @param bool $force
	 */
	protected function getValidators($force = false)
	{
		if ($this->_validators === null || $force) {
			$this->_validators = array();
			$rules = $this->rules();
			foreach ($rules as $rule) {
				$rule['model'] = $this;
				$this->_validators[] = $this->createValidatorFromSettings($rule);
			}
		}
		return $this->_validators;
	}

	/**
	 * Создает валидатор из массива настроек
	 * @param array $settings
	 * @return \bxar\IValidator
	 */
	protected function createValidatorFromSettings(array $settings)
	{
		return \bxar\validators\Factory::create($settings);
	}



	/**
	 * Возвращает все атрибуты модели
	 * @return array
	 */
	public function getAttributes()
	{
		$return = array();
		foreach ($this->getAttributesNames() as $name) {
			$return[$name] = $this->getAttribute($name);
		}
		return $return;
	}

	/**
	 * Возвращает атрибут модели по указанному имени
	 * @param string $name
	 * @return null|\bxar\IAttribute
	 */
	public function getAttribute($name)
	{
		$name = $this->prepareAttributeName($name);
		return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
	}

	/**
	 * Проверяет существует ли у модели указанный атрибут
	 * @param string $name
	 * @return bool
	 */
	public function issetAttribute($name)
	{
		return $this->getAttribute($name) !== null;
	}

	/**
	 * Добавляет новый атрибут модели
	 * @param string $name
	 * @param \bxar\IAttribute $attr
	 */
	protected function setAttribute($name, \bxar\IAttribute $attr)
	{
		$name = $this->prepareAttributeName($name);
		$this->_attributes[$name] = $attr;
	}

	/**
	 * Удаляет указанный атрибут модели
	 * @param string $name
	 */
	protected function unsetAttribute($name)
	{
		$name = $this->prepareAttributeName($name);
		unset($this->_attributes[$name]);
	}

	/**
	 * Возвращает список всех имен атрибутов модели
	 * @return array
	 */
	protected function getAttributesNames()
	{
		return array_keys($this->_attributes);
	}

	/**
	 * Приводит имена атрибутов к общему виду
	 * @param string $name
	 * @return string
	 */
	protected function prepareAttributeName($name)
	{
		return strtolower(trim($name));
	}



	/**
	 * Возвращает значения всех атрибутов
	 * @return array
	 */
	public function getValues()
	{
		$attrs = $this->getAttributes();
		$return = array();
		foreach ($attrs as $key => $attr) {
			$return[$key] = $attr->getValue();
		}
		return $return;
	}

	/**
	 * Задает значения всех аттрибутов
	 * @param array $values
	 */
	public function setValues(array $values)
	{
		foreach ($values as $key => $value) {
			$attr = $this->getAttribute($key);
			if ($attr) $attr->setValue($value);
		}
	}



	/**
	 * Запускает событие по атрибутам модели
	 * @param string $eventName
	 * @return bool
	 */
	protected function riseEvent($eventName)
	{
		$return = true;
		$attributes = $this->getAttributes();
		foreach ($attributes as $attr) {
			$ret = $attr->riseEvent($eventName);
			if ($ret === false) $return = false;
		}
		return $return;
	}



	/**
	 * Возвращает список всех ошибок атрибутов модели
	 * @return array
	 */
	public function getErrors()
	{
		$attrs = $this->getAttributes();
		$return = array();
		foreach ($attrs as $key => $attr) {
			if (!$attr->hasErrors()) continue;
			$return[$key] = $attr->getErrors();
		}
		return $return;
	}

	/**
	 * Есть ли в модели ошибки
	 * @return bool
	 */
	public function hasErrors()
	{
		$attrs = $this->getAttributes();
		$return = false;
		foreach ($attrs as $key => $attr) {
			if ($attr->hasErrors()) {
				$return = true;
				break;
			}
		}
		return $return;
	}

	/**
	 * Очищает список ошибок
	 */
	public function clearErrors()
	{
		$attrs = $this->getAttributes();
		foreach ($attrs as $key => $attr) {
			$attr->setErrors();
		}
	}



	/**
	 * @param string $value
	 * @return \bxar\IActiveRecord
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