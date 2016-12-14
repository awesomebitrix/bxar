<?php

namespace marvin255\bxar;

/**
 * Интерфейс, который описывает класс для хранилища данных.
 */
interface IRepo
{
    /**
     * Ищет и возвращает все записи из хранилища, которые подходят под запрос.
     *
     * @param \marvin255\bxar\IQuery $query
     *
     * @return array
     */
    public function searchAll(\marvin255\bxar\IQuery $query);

    /**
     * Считает количество записей из хранилища, которые подходят под запрос.
     *
     * @param \marvin255\bxar\IQuery $query
     *
     * @return int
     */
    public function count(\marvin255\bxar\IQuery $query);

    /**
     * Ищет и возвращает первую из подходящих записей из хранилища,
     * которая подходит под запрос.
     *
     * @param \marvin255\bxar\IQuery $query
     *
     * @return \marvin255\bxar\IModel|null
     */
    public function search(\marvin255\bxar\IQuery $query);

    /**
     * Создает новую запись в хранилище или обновляет старую.
     *
     * @param \marvin255\bxar\IModel $model
     *
     * @return bool
     */
    public function save(\marvin255\bxar\IModel $model);

    /**
     * Удаляет запись из хранилища.
     *
     * @param \marvin255\bxar\IModel $model
     *
     * @return bool
     */
    public function delete(\marvin255\bxar\IModel $model);

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
