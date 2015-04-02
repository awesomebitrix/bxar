<?php

namespace bx\ar;

/**
 * Базовый класс для active record
 */
abstract class ActiveRecord
{
	/**
	 * @var array атрибуты
	 */
	protected $_attributes = array();
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
		$this->loadAttributes();
	}

	/**
	 * Создает атрибуты Active Record
	 */
	protected function loadAttributes()
	{
		$atts = $this->getAttributesDescriptions();
		$this->_attributes = is_array($atts) ? $atts : array();
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