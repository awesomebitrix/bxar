<?php

namespace bxar;

/**
 * Интерфейс для active record
 */
interface IActiveRecord
{
	/**
	 * Создает атрибуты Active Record
	 * @param array $init
	 */
	public function initAttributes(array $init);

	/**
	 * Удаляет запись
	 * @return bool
	 */
	public function delete();

	/**
	 * Сохраняет запись
	 * @return bool
	 */
	public function save();

	/**
	 * Есть ли данна запись в базе данных
	 * @return bool
	 */
	public function isNew();



	/**
	 * @param array $value
	 * @param string $scenario
	 */
	public function __construct(array $value = null, $scenario = 'default');



	/**
	 * Проверяет поля модели
	 * @param array $fields
	 * @param bool $setErrors
	 * @return bool
	 */
	public function validate(array $fields = null, $setErrors = true);



	/**
	 * Возвращает все атрибуты модели
	 * @return array
	 */
	public function getAttributes();

	/**
	 * Возвращает атрибут модели по указанному имени
	 * @param string $name
	 * @return null|\bxar\IAttribute
	 */
	public function getAttribute($name);

	/**
	 * Проверяет существует ли у модели указанный атрибут
	 * @param string $name
	 * @return bool
	 */
	public function issetAttribute($name);



	/**
	 * Возвращает значения всех атрибутов
	 * @return array
	 */
	public function getValues();

	/**
	 * Задает значения всех аттрибутов
	 * @param array $values
	 */
	public function setValues(array $values);



	/**
	 * Возвращает список всех ошибок атрибутов модели
	 * @return array
	 */
	public function getErrors();

	/**
	 * Есть ли в модели ошибки
	 * @return bool
	 */
	public function hasErrors();

	/**
	 * Очищает список ошибок
	 */
	public function clearErrors();



	/**
	 * @param string $value
	 * @return \bxar\IActiveRecord
	 */
	public function setScenario($value);

	/**
	 * @return string
	 */
	public function getScenario();
}