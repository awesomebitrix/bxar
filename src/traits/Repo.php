<?php

namespace marvin255\bxar\traits;

use InvalidArgumentException;
use LogicException;

/**
 * Трэйт, который реализует базовые функции IRepo.
 */
trait Repo
{
    /**
     * @var array
     */
    protected $fieldsHandlers = [];

    /**
     * Возвращает объект-обработчик для поля.
     *
     * @param string $name
     *
     * @return \marvin255\bxar\IField
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function getFieldHandler($name)
    {
        $name = $this->encodeFieldName($name);
        if (empty($this->fieldsHandlers[$name])) {
            $descriptions = $this->getFieldsDescriptions();
            if (!isset($descriptions[$name])) {
                throw new InvalidArgumentException('Field not found: '.$name);
            }
            $field = $this->createFieldHandler($descriptions[$name]);
            if (!($field instanceof \marvin255\bxar\IField)) {
                throw new LogicException('Field must implements \marvin255\bxar\IField');
            }
            $this->fieldsHandlers[$name] = $field;
        }
        return $this->fieldsHandlers[$name];
    }

    /**
     * Создает объект-обработчик для поля.
     *
     * @param array $description
     *
     * @return \marvin255\bxar\IField
     */
    protected function createFieldHandler(array $description)
    {
        return null;
    }

    /**
     * @var array
     */
    protected $fieldsDescriptions = null;

    /**
     * Возвращает список с описаниями полей
     *
     * @return array
     */
    public function getFieldsDescriptions()
    {
        if ($this->fieldsDescriptions === null) {
            $fields = $this->loadFieldsDescriptions();
            $this->fieldsDescriptions = $fields ?: [];
        }
        return $this->fieldsDescriptions;
    }

    /**
     * Возвращает список с описаниями полей из бд или иного источника,
     * чья обработка занимает время
     *
     * @return array
     */
    protected function loadFieldsDescriptions()
    {
        return [];
    }

    /**
     * @var string
     */
    protected $modelClass = '';

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
        $this->modelClass = $modelClass;
        return $this;
    }

    /**
     * Возвращает класс моделей, которые будет создавать хранилище.
     *
     * @return string
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }

    /**
     * Обрабатывает название поля для того,
     * чтобы привести все названия к единообразю.
     *
     * @param string $name
     *
     * @return string
     */
    public function encodeFieldName($name)
    {
        return strtolower(trim($name));
    }
}
