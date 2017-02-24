<?php

namespace marvin255\bxar\repo;

/**
 * Интерфейс, который описывает класс для специальных функций хранилища данных.
 * Например, для инфоблоков или orm. Крайне не рекомендуется использовать класс
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
     * @param \marvin255\bxar\repo\RepoInterface $repo
     * @param array                              $fields
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function createFieldHandler($name, \marvin255\bxar\repo\RepoInterface $repo, array $fields);

    /**
     * На основании объекта $query пробует найти соответствующие данные
     * в хранилище и вернуть массив моделей.
     *
     * @param \marvin255\bxar\query\QueryInterface $query
     * @param array                                $fields
     *
     * @return array
     */
    public function search(\marvin255\bxar\query\QueryInterface $query, array $fields);

    /**
     * Возвращает количество записей в хранилище, удовлетворяющих условию из объекта
     * $query.
     *
     * @param \marvin255\bxar\query\QueryInterface $query
     * @param array                                $fields
     *
     * @return int
     */
    public function count(\marvin255\bxar\query\QueryInterface $query, array $fields);

    /**
     * Пробует обновить или создать новую запись в хранилище для данных, которые
     * содержатся в модели из параметра $model.
     *
     * @param \marvin255\bxar\model\ModelInterface $model
     * @param array                                $fields
     *
     * @return bool
     */
    public function save(\marvin255\bxar\model\ModelInterface $model, array $fields);

    /**
     * Проверяет поля модели перед записью в хранилище.
     *
     * @param \marvin255\bxar\model\ModelInterface $model
     * @param array                                $fields
     *
     * @return bool
     */
    public function validate(\marvin255\bxar\model\ModelInterface $model, array $fields);

    /**
     * Пробует удалить запись в хранилище для данных, которые
     * содержатся в модели из параметра $model.
     *
     * @param \marvin255\bxar\model\ModelInterface $model
     * @param array                                $fields
     *
     * @return bool
     */
    public function delete(\marvin255\bxar\model\ModelInterface $model, array $fields);
}
