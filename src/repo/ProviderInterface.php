<?php

namespace marvin255\bxar\repo;

/**
 * Интерфейс, который описывает класс для специальных функций хранилища данных.
 * Например, для инфоблоков или orm. Крайне не реекомендуется использовать класс
 * провайдера напрямую, без фасада хранилища.
 */
interface ProviderInterface
{
    /**
     * Возвращает массив с описаниями полей хранилища.
     * Где ключ - название поля, а значение массив со свойствами поля.
     *
     * @return array
     */
    public function getFieldsDescription();

    /**
     * Создает объект-обработчик поля хранилища с названием из $name и свойствами
     * из $fieldData для модели. Привязывает поле к хранилищу из $repo.
     *
     * @param string                             $name
     * @param array                              $fieldData
     * @param \marvin255\bxar\repo\RepoInterface $repo
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function createFieldHandler($name, array $fieldData, \marvin255\bxar\repo\RepoInterface $repo);

    /**
     * На основании объекта $query пробует найти соответствующие данные
     * в хранилище и вернуть массив моделей.
     *
     * @param \marvin255\bxar\query\QueryInterface $query
     *
     * @return array
     */
    public function search(\marvin255\bxar\query\QueryInterface $query);

    /**
     * Возвращает количество записей в хранилище, удовлетворяющих условию из объекта
     * $query.
     *
     * @param \marvin255\bxar\query\QueryInterface $query
     *
     * @return int
     */
    public function count(\marvin255\bxar\query\QueryInterface $query);

    /**
     * Пробует обновить или создать новую запись в хранилище для данных, которые
     * содержатся в модели из параметра $model.
     *
     * @param \marvin255\bxar\model\ModelInterface $model
     *
     * @return bool
     */
    public function save(\marvin255\bxar\model\ModelInterface $model);

    /**
     * Пробует удалить запись в хранилище для данных, которые
     * содержатся в модели из параметра $model.
     *
     * @param \marvin255\bxar\model\ModelInterface $model
     *
     * @return bool
     */
    public function delete(\marvin255\bxar\model\ModelInterface $model);
}
