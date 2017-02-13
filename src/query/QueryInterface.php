<?php

namespace marvin255\bxar\query;

/**
 * Интерфейс для класса, который позволяет составить запрос к хранилищу.
 * Все запросы к хранилищу, должны быть оформлены с помощью объекта, реализующего
 * данный интерфейс.
 */
interface QueryInterface
{
    /**
     * Задает атрибуты, которые необходимо вернуть в каждой модели выборки.
     *
     * @param array $value
     *
     * @return \marvin255\bxar\query\QueryInterface
     */
    public function setSelect(array $value = null);

    /**
     * Возвращает атрибуты, которые необходимо вернуть в каждой модели выборки.
     *
     * @return array
     */
    public function getSelect();

    /**
     * Задает порядок сортировки для моделей в выборке. Ключи массива - название атрибутов,
     * значения - порядок сортировки.
     *
     * @param array $value
     *
     * @return \marvin255\bxar\query\QueryInterface
     */
    public function setOrder(array $value = null);

    /**
     * Возвращает порядок сортировки для моделей в выборке.
     *
     * @return array
     */
    public function getOrder();

    /**
     * Задает фильтр для выборки.
     *
     * @param array $value
     *
     * @return \marvin255\bxar\query\QueryInterface
     */
    public function setFilter(array $value = null);

    /**
     * Возвращает фильтр для выборки.
     *
     * @return array
     */
    public function getFilter();

    /**
     * Задает ограничение по количеству загружаемых моделей в выборке.
     *
     * @param int $value
     *
     * @return \marvin255\bxar\query\QueryInterface
     */
    public function setLimit($value);

    /**
     * Возвращает ограничение по количеству загружаемых моделей в выборке.
     *
     * @return int
     */
    public function getLimit();

    /**
     * Задает смещение для выборки.
     *
     * @param int $value
     *
     * @return \marvin255\bxar\query\QueryInterface
     */
    public function setOffset($value);

    /**
     * Возвращает смещение для выборки.
     *
     * @return int
     */
    public function getOffset();

    /**
     * Задает название атрибута, значения которого будут ключами в массиве выборки.
     *
     * @param string $value
     *
     * @return \marvin255\bxar\query\QueryInterface
     */
    public function setIndex($value);

    /**
     * Возвращает название атрибута, значения которого будут ключами в массиве выборки.
     *
     * @return string
     */
    public function getIndex();

    /**
     * Очищает все параметры запроса.
     *
     * @return \marvin255\bxar\query\QueryInterface
     */
    public function clear();

    /**
     * Задает привязку к хранилищу для вызова шорткатов, нужно специально для
     * красивой и логичной цепочки $query->setFilter(['ACTIVE' => 'Y'])->setLimit(10)->all().
     *
     * @param \marvin255\bxar\repo\RepoInterface $repo
     */
    public function setRepo(\marvin255\bxar\repo\RepoInterface $repo);

    /**
     * Шорткат для функции all хранилища.
     *
     * @return array
     */
    public function all();

    /**
     * Шорткат для функции one хранилища.
     *
     * @return null|\marvin255\bxar\model\ModelInterface
     */
    public function one();

    /**
     * Шорткат для функции count хранилища.
     *
     * @return int
     */
    public function count();
}
