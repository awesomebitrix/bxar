<?php

namespace marvin255\bxar\iblock;

use marvin255\bxar\IRepo;
use marvin255\bxar\TRepo;

/**
 * Класс для хранилища данных из определенного инфоблока.
 */
class Repo implements IRepo
{
    use TRepo;

    /**
     * Возвращает одну запись из хранилища на основании данных параметра $query.
     *
     * @param \bxar\IQuery $query
     *
     * @return \bxar\IModel|null
     */
    public function search(IQuery $query)
    {
    }

    /**
     * Возвращает массив записей из хранилища
     * на основании данных параметра $query.
     *
     * @param \bxar\IQuery $query
     *
     * @return array
     */
    public function searchAll(IQuery $query)
    {
    }

    /**
     * Возвращает количество элементов в хранилище,
     * которые подходят под запрос из $query.
     *
     * @param \bxar\IQuery $query
     *
     * @return int
     */
    public function count(IQuery $query)
    {
    }

    /**
     * Пробует добавить запись в хранилище, если ее еще нет, и обновить,
     * если такая уже существует
     *
     * @param \bxar\IModel $model
     *
     * @return bool
     */
    public function save(IModel $model)
    {
    }

    /**
     * Пробует удалить запись из репозитория.
     *
     * @param \bxar\IModel $model
     *
     * @return bool
     */
    public function delete(IModel $model)
    {
    }

    /**
     * Возвращает массив с описанием полей модели для данного хранилища
     * ключами служат названия полей, а значениями - описания.
     *
     * @return array
     */
    public function getFieldsDescription()
    {
    }

    /**
     * Возвращает объект, который представляет собой
     * обработчик для конкретного поля.
     *
     * @param string $name
     *
     * @return \bxar\IField
     */
    public function getField($name)
    {
    }

    /**
     * Создает объект обработчика для поля модели по описанию из массива.
     *
     * @param array $description
     *
     * @return \bxar\IField
     */
    protected function createField(array $description)
    {
    }
}
