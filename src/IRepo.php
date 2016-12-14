<?php

namespace marvin255\bxar;

/**
 * Интерфейс, который описывает класс для хранилища данных.
 */
interface IRepo
{
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
    public function getFieldHandler($name);

    /**
     * Возвращает список с описаниями полей
     *
     * @return array
     */
    public function getFieldsDescriptions();

    /**
     * Задает класс моделей, которые будет создавать хранилище.
     *
     * @param string $modelClass
     *
     * @return \marvin255\bxar\IRepo
     *
     * @throws \InvalidArgumentException
     */
    public function setModelClass($modelClass);

    /**
     * Возвращает класс моделей, которые будет создавать хранилище.
     *
     * @return string
     */
    public function getModelClass();

    /**
     * Обрабатывает название поля для того,
     * чтобы привести все названия к единообразю.
     *
     * @param string $name
     *
     * @return string
     */
    public function encodeFieldName($name);
}
