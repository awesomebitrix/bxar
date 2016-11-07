<?php

namespace marvin255\bxar;

use InvalidArgumentException;

/**
 * Трэйт, который реализует базовые функции IRepo
 */
trait TRepo
{
    /**
     * @var \marvin255\bxar\IQuery
     */
    protected $_query = '';

    /**
     * Задает объект запроса для данного хранилища
     * @param \marvin255\bxar\IQuery $query
     * @return \marvin255\bxar\IRepo
     */
    public function setQuery(IQuery $query)
    {
        $this->_query = $query;
        return $this;
    }

    /**
     * Возвращает объект запроса для данного хранилища
     * @return \marvin255\bxar\IQuery
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * Подготавливает запрос к новому поиску
     * @return \marvin255\bxar\IQuery
     */
    public function newQuery()
    {
        return $this->getQuery()->clear()->setRepo($this);
    }

    /**
     * @var string
     */
    protected $_modelClass = '';

    /**
     * Задает класс моделей, которые будет создавать хранилище.
     *
     * @param string $modelClass
     *
     * @return \marvin255\bxar\IRepo
     *
     * @throws \InvalidArgumentException
     */
    public function setModelClass($modelClass)
    {
        if (!is_subclass_of($modelClass, '\\marvin255\bxar\\IModel')) {
            throw new InvalidArgumentException("Model class {$modelClass} must implements \marvin255\bxar\IModel");
        }
        $this->_modelClass = $modelClass;
    }

    /**
     * Возвращает класс моделей, которые будет создавать хранилище.
     *
     * @return string
     */
    public function getModelClass()
    {
        return $this->_modelClass;
    }

    /**
     * Обрабатывает название поля для того,
     * чтобы привести все названия к единообразю.
     *
     * @param string $name
     *
     * @return string
     */
    public function escapeFieldName($name)
    {
        return strtolower(trim($name));
    }
}
