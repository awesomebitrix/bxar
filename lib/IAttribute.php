<?php

namespace bx\ar;

/**
 * Интерфейс для атрибута модели
 */
interface IAttribute
{
	/**
	 * Задает настройки поля
	 * @param array $options
	 */
	public function initAttributes(array $options);



	/**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value);

	/**
	 * Возвращает значение атрибута
	 * @return mixed
	 */
	public function getValue();



	/**
	 * Задает код атрибута
	 * @param string $value
	 */
	public function setCode($value);

	/**
	 * Возвращает код атрибута
	 * @return mixed
	 */
	public function getCode();



	/**
	 * Задает параметры атрибута
	 * @param array $value
	 */
	public function setParams(array $value = null);

	/**
	 * Возвращает параметры атрибута
	 * @return array
	 */
	public function getParams();

	/**
	 * Возвращает значение указанного параметра
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function getParam($name, $default = null);



	/**
	 * Задает значение атрибута
	 * @param \bx\ar\IActiveRecord $model
	 */
	public function setModel(\bx\ar\IActiveRecord $model);

	/**
	 * Возвращает значение атрибута
	 * @return \bx\ar\IActiveRecord
	 */
	public function getModel();


	/**
	 * Запускает событие по атрибутам модели
	 * @param string $eventName
	 * @return bool
	 */
	public function riseEvent($eventName);


	/**
	 * Возвращает список ошибок для атрибута
	 * @return array
	 */
	public function getErrors();

	/**
	 * Задает список ошибок для атрибута
	 * @param array $value
	 */
	public function setErrors(array $value = array());

	/**
	 * Добавляет ошибку в список для атрибута
	 * @param string $value
	 */
	public function addError($value);

	/**
	 * Проверяет есть ли ошибки у атрибута
	 * @param string $value
	 */
	public function hasErrors();
}