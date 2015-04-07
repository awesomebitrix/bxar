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
	public function __construct($scenario = 'default');

	/**
	 * Создает атрибуты Active Record
	 * @param array $init
	 */
	public function initAttributes(array $init, $force = false);

	/**
	 * Проверяет поля модели
	 * @param array $fields
	 * @param bool $setErrors
	 * @return bool
	 */
	public function validate(array $fields = null, $setErrors = true);

	/**
	 * Возвращает массив с атрибутами модели
	 * @return array
	 */
	public function getAttributes();

	/**
	 * Возвращает атрибут модели по его имени
	 * @param string $name
	 * @return \bx\ar\IAttribute
	 */
	public function getAttribute($name);

	/**
	 * Задает значение атрибута модели
	 * @param string $name
	 * @param mixed $value
	 */
	public function setAttributeValue($name, $value);

	/**
	 * Задает значение атрибута модели
	 * @param string $name
	 */
	public function getAttributeValue($name);

	/**
	 * Задает значения атрибутов из массива
	 * @param array $value
	 */
	public function setAttributesValues(array $value);

	/**
	 * Возвращает значения всех атрибутов
	 * @return array
	 */
	public function getAttributesValues();

	/**
	 * Удаляет запись
	 * @return bool
	 */
	public function delete();

	/**
	 * Сохраняет запись
	 */
	public function save();

	/**
	 * @param string $value
	 * @return \bx\ar\IActiveRecord
	 */
	public function setScenario($value);

	/**
	 * @return string
	 */
	public function getScenario();
}