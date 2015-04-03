<?php

namespace bx\ar;

/**
 * Интерфейс для active record
 */
interface IActiveRecord
{
	/**
	 * Создает объект для поиска нужных записей
	 * @param array $filter
	 * @param \bx\ar\IFinder $finder
	 * @return \bx\ar\IFinder
	 */
	public static function find(array $filter = null, \bx\ar\IFinder $finder = null);

	/**
	 * @param string $scenario
	 * @param array $value
	 */
	public function __construct($scenario = 'default', array $value = array());

	/**
	 * Инициирует запись первоначальными значениями.
	 * В этой функции можно перехватить создание объекта записи и поправить его структуру
	 * @param array $value
	 */
	public function init(array $value);

	/**
	 * Проверяет поля модели
	 * @param array $fields
	 * @param bool $setErrors
	 * @return bool
	 */
	public function validate(array $fields = null, $setErrors = true);

	/**
	 * Возвращает атрибут
	 * @param string $name
	 * @throw \bx\ar\Exception
	 * @return \bx\ar\IAttribute
	 */
	public function getAttribute($name);

	/**
	 * @param string $value
	 * @return \bx\ar\IActiveRecord
	 */
	public function setScenario($value);

	/**
	 * @return string
	 */
	public function getScenario();

	/**
	 * @return array
	 */
	public function toArray();
}