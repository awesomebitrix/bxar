<?php

namespace bxar;

use InvalidArgumentException;

/**
 * Трэйт, который реализует базовые функции IRepo
 */
interface TRepo
{
    /**
     * @var string
     */
    protected $_modelClass = '';

    /**
     * Задает класс моделей, которые будет создавать хранилище.
     *
     * @param string $modelClass
     *
     * @return \bxar\IRepo
     *
     * @throws \InvalidArgumentException
     */
    public function setModelClass($modelClass)
    {
        if (!is_subclass_of($modelClass, '\\bxar\\IModel')) {
            throw new InvalidArgumentException("Model class {$modelClass} must implements \bxar\IModel");
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
}
