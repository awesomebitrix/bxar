<?php

namespace bxar;

use InvalidArgumentException;

/**
 * Трэйт, который реализует базовые функции IRepo
 */
interface TRepo
{
    /**
     * @var \bxar\IFieldManager
     */
    protected $_fieldManager = null;

    /**
     * Задает менеджер полей для данного хранилища.
     *
     * @param \bxar\IFieldManager $manager
     *
     * @return \bxar\IRepo
     */
    public function setFieldManager(IFieldManager $manager)
    {
        $this->_fieldManager = $manager;
    }

    /**
     * Возвращает менеджер полей для данного хранилища.
     *
     * @return \bxar\IFieldManager
     */
    public function getFieldManager()
    {
        return $this->_fieldManager;
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
