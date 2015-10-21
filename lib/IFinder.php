<?php

namespace bxar;

/**
 * Интерфейс для атрибута модели
 */
interface IFinder
{
	/**
	 * @param array $filter
	 * @param string $arClass
	 * @return \bxar\IFinder
	 */
	public static function find(array $filter = null, $arClass = null);


	/**
	 * @param string $value
	 * @return \bxar\IFinder
	 */
	public function setArClass($value);

	/**
	 * @return string
	 */
	public function getArClass();


	/**
	 * @param array $value
	 * @return \bxar\IFinder
	 */
	public function setOrder(array $value);

	/**
	 * @return array
	 */
	public function getOrder();


	/**
	 * @param array $value
	 * @return \bxar\IFinder
	 */
	public function setFilter(array $value);

	/**
	 * @param array $value
	 * @return \bxar\IFinder
	 */
	public function mergeFilterWith(array $value);

	/**
	 * @return array
	 */
	public function getFilter();


	/**
	 * @param int $value
	 * @return \bxar\IFinder
	 */
	public function setLimit($value);

	/**
	 * @return int
	 */
	public function getLimit();


	/**
	 * @param int $value
	 * @return \bxar\IFinder
	 */
	public function setOffset($value);

	/**
	 * @return int
	 */
	public function getOffset();


	/**
	 * @param int $value
	 * @return \bxar\IFinder
	 */
	public function setAsArray($value = true);

	/**
	 * @return bool
	 */
	public function getAsArray();


	/**
	 * @param string $value
	 * @return \bxar\IFinder
	 */
	public function setIndex($value);

	/**
	 * @return string
	 */
	public function getIndex();


	/**
	 * Находит один элемент
	 * @return \bxar\IActiveRecord
	 */
	public function one();

	/**
	 * Находит несколько элементов
	 * @return array
	 */
	public function all();

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