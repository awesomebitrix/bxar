<?php

namespace bxar;

/**
 * Интерфейс, который описывает класс хранилища данных.
 * Хранилище должно осуществлять все основные операции, связанные с моделями:
 * - поиск
 * - создание
 * - обновление
 * - удаление
 */
interface IRepo
{
    /**
     * Возвращает одну запись из хранилища на основании данных параметра $query
     * @param \bxar\IQuery $query
     * @return \bxar\IModel|null
     */
    public function search(IQuery $query);

    /**
     * Возвращает массив записей из хранилища
     * на основании данных параметра $query
     * @param \bxar\IQuery $query
     * @return array
     */
    public function searchAll(IQuery $query);

    /**
     * Возвращает количество элементов в хранилище,
     * которые подходят под запрос из $query
     * @param \bxar\IQuery $query
     * @return int
     */
    public function count(IQuery $query);

    /**
     * Пробует добавить запись в хранилище, если ее еще нет, и обновить,
     * если такая уже существует
     * @param \bxar\IModel $model
     * @return bool
     */
    public function save(IModel $model);

    /**
     * Пробует удалить запись из репозитория
     * @param \bxar\IModel $model
     * @return bool
     */
    public function delete(IModel $model);

    /**
     * Задает менеджер полей для данного хранилища
     * @param \bxar\IFieldManager $manager
     * @return \bxar\IRepo
     */
    public function setFieldManager(IFieldManager $manager);

    /**
     * Возвращает менеджер полей для данного хранилища
     * @return \bxar\IFieldManager
     */
    public function getFieldManager();

    /**
     * Задает класс моделей, которые будет создавать хранилище
     * @param string $modelClass
     * @return \bxar\IRepo
     */
    public function setModelClass($modelClass);

    /**
     * Возвращает класс моделей, которые будет создавать хранилище
     * @return string
     */
    public function getModelClass();
}
