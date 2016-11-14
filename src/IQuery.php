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
    public function setSelect(array $value = null);

    /**
     * @return array
     */
    public function getSelect();

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setOrder(array $value = null);

    /**
     * @return array
     */
    public function getOrder();

    /**
     * @param array $value
     *
     * @return \marvin255\bxar\IQuery
     */
    public function setFilter(array $value = null);

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
     * @return \marvin255\bxar\IQuery
     */
    public function clear();
}
