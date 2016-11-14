<?php

namespace marvin255\bxar;

/**
 * Интерфейс, который описывает класс хранилища данных.
 * Хранилище должно осуществлять все основные операции, связанные с моделями:
 * - поиск
 * - создание
 * - обновление
 * - удаление.
 */
interface IRepo
{
    /**
     * Возвращает одну запись из хранилища на основании данных параметра $query.
     *
     * @param \marvin255\bxar\IQuery $query
     *
     * @return \marvin255\bxar\IModel|null
     */
    public function search(IQuery $query);

    /**
     * Возвращает массив записей из хранилища
     * на основании данных параметра $query.
     *
     * @param \marvin255\bxar\IQuery $query
     *
     * @return array
     */
    public function searchAll(IQuery $query);

    /**
     * Возвращает количество элементов в хранилище,
     * которые подходят под запрос из $query.
     *
     * @param \marvin255\bxar\IQuery $query
     *
     * @return int
     */
    public function count(IQuery $query);

    /**
     * Пробует добавить запись в хранилище, если ее еще нет, и обновить,
     * если такая уже существует
     *
     * @param \marvin255\bxar\IModel $model
     *
     * @return bool
     */
    public function save(IModel $model);

    /**
     * Пробует удалить запись из репозитория.
     *
     * @param \marvin255\bxar\IModel $model
     *
     * @return bool
     */
    public function delete(IModel $model);

    /**
     * Задает класс моделей, которые будет создавать хранилище.
     *
     * @param string $modelClass
     *
     * @return \marvin255\bxar\IRepo
     */
    public function setModelClass($modelClass);

    /**
     * Возвращает класс моделей, которые будет создавать хранилище.
     *
     * @return string
     */
    public function getModelClass();

    /**
     * Возвращает массив с описанием полей модели для данного хранилища
     * ключами служат названия полей, а значениями - описания.
     *
     * @return array
     */
    public function getFieldsDescription();

    /**
     * Возвращает объект, который представляет собой
     * обработчик для конкретного поля.
     *
     * @param string $name
     *
     * @return \marvin255\bxar\IField
     */
    public function getField($name);

    /**
     * Обрабатывает название поля для того,
     * чтобы привести все названия к единообразю.
     *
     * @param string $name
     *
     * @return string
     */
    public function escapeFieldName($name);
}
