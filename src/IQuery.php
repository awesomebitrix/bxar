<?php

namespace marvin255\bxar;

/**
 * Интерфейс, который описывает класс для поиска данных в хранилище.
 */
interface IQuery
{
    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setSelect(array $value = array());

    /**
     * @return array
     */
    public function getSelect();

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setOrder(array $value = array());

    /**
     * @return array
     */
    public function getOrder();

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setFilter(array $value = array());

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function andFilter(array $value);

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function orFilter(array $value);

    /**
     * @return array
     */
    public function getFilter();

    /**
     * @param int $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setLimit($value);

    /**
     * @return int
     */
    public function getLimit();

    /**
     * @param int $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setOffset($value);

    /**
     * @return int
     */
    public function getOffset();

    /**
     * @param string $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setIndex($value);

    /**
     * @return string
     */
    public function getIndex();

    /**
     * @param \marvin255\bxar\IRepo $repo
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setRepo(IRepo $repo);

    /**
     * @return \marvin255\bxar\IRepo
     */
    public function getRepo();

    /**
     * Возвращает одну запись из хранилища.
     * Shortcut для соответствующего метода хранилища.
     *
     * @return \marvin255\bxar\IModel|null
     */
    public function search();

    /**
     * Возвращает массив записей из хранилища
     * Shortcut для соответствующего метода хранилища.
     *
     * @return array
     */
    public function searchAll();

    /**
     * Возвращает количество элементов в хранилище.
     * Shortcut для соответствующего метода хранилища.
     *
     * @return int
     */
    public function count();

    /**
     * @return \marvin255\bxar\IQuery
     */
    public function clear();
}
