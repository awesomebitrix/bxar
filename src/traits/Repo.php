<?php

namespace marvin255\bxar\traits;

use InvalidArgumentException;

/**
 * Трэйт, который реализует базовые функции IRepo.
 */
trait Repo
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
        return $this;
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