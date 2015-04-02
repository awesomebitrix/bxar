<?php

namespace bx\ar;

/**
 * Интерфейс для атрибута модели
 */
interface IFinder
{
	/**
	 * @param string $value
	 * @return \bx\ar\IFinder
	 */
	public function setArClass($value);

	/**
	 * @return string
	 */
	public function getArClass();


	/**
	 * @param array $value
	 * @return \bx\ar\IFinder
	 */
	public function setOrder(array $value);

	/**
	 * @return array
	 */
	public function getOrder();


	/**
	 * @param array $value
	 * @return \bx\ar\IFinder
	 */
	public function setFilter(array $value);

	/**
	 * @return array
	 */
	public function getFilter();


	/**
	 * @param int $value
	 * @return \bx\ar\IFinder
	 */
	public function setLimit($value);

	/**
	 * @return int
	 */
	public function getLimit();


	/**
	 * @param int $value
	 * @return \bx\ar\IFinder
	 */
	public function setOffset($value);

	/**
	 * @return int
	 */
	public function getOffset();


	/**
	 * Находит один элемент
	 * @return \bx\ar\IActiveRecord
	 */
	public function find();

	/**
	 * Находит несколько элементов
	 * @return array
	 */
	public function findAll();

	/**
	 * Находит количество элементов по запросу
	 * @return int
	 */
	public function count();


	/**
	 * Кэширует запрос на указанное количество секунд
	 * @param int $time
	 */
	public function cache($time);
}