<?php

namespace bxar\iblock;

use bxar\IRepo;
use bxar\TRepo;

/**
 * Класс для хранилища данных из определенного инфоблока
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
}
