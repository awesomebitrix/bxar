<?php

namespace marvin255\bxar\repo;

/**
 * Базовый класс для хранилища данных. Хранилище служит в качестве фасада
 * для поставщика данных @see \marvin255\bxar\repo\ProviderInterface. Для каждой сущности
 * будет инициироваться один и тот же класс хранилища, но разные поставщики.
 * Таким образом мы сожем перенести максимально много логики в класс хранилища
 * и сделать легкие и удобные для разработки классы поставщиков.
 * Кроме того, в значительной мере упрощается тестирование класса хранилища
 * в связи с особенностями тестирования в битриксе.
 */
interface RepoInterface
{
    /**
     * Провайдер и класс модели передаются исключительно в конструкторе
     * и не могут быть изменены в процессе существования хранилища.
     * Искуственное ограничение, специально, чтобы сократить возможное
     * число ошибок при работе с хранилищем.
     *
     * @param \marvin255\bxar\repo\ProviderInterface $provider
     * @param string                                 $modelName
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(\marvin255\bxar\repo\ProviderInterface $provider, $modelName);

    /**
     * Возвращает название класса для моделей, которые будет создавать хранилище.
     *
     * @return string
     */
    public function getModelName();

    /**
     * Возвращает провайдер, который служит для общения с конкретной реализацией
     * хранилища данных.
     *
     * @return \marvin255\bxar\repo\ProviderInterface
     */
    public function getProvider();

    /**
     * Возвращает массив с описаниями полей хранилища.
     * Где ключ - название поля, а значение массив со свойствами поля.
     *
     * @return array
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function getFieldsDescription();

    /**
     * Создает объект-обработчик поля хранилища с названием из $fieldName для модели.
     *
     * @param string $fieldName
     *
     * @return \marvin255\bxar\model\FieldInterface
     *
     * @throws \InvalidArgumentException
     * @throws \marvin255\bxar\repo\Exception
     */
    public function createFieldHandler($fieldName);

    /**
     * Псевдоним для функции all, который возвращает не массив моделей, а только
     * одну первую модель, либо null, если данные не найдены.
     *
     * @param \marvin255\bxar\query\QueryInterface $query
     *
     * @return \marvin255\bxar\model\ModelInterface|null
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function one(\marvin255\bxar\query\QueryInterface $query);

    /**
     * На основании объекта $query пробует найти соответствующие данные
     * в хранилище и вернуть массив моделей, с классом, указанным в конструкторе
     * хранилища. Передает запрос в провайдер и обрабатывает результат,
     * который возвращает провайдер.
     *
     * @param \marvin255\bxar\query\QueryInterface $query
     *
     * @return array
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function all(\marvin255\bxar\query\QueryInterface $query);

    /**
     * Возвращает количество записей в хранилище, удовлетворяющих условию из объекта
     * $query.
     *
     * @param \marvin255\bxar\query\QueryInterface $query
     *
     * @return int
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function count(\marvin255\bxar\query\QueryInterface $query);

    /**
     * Пробует обновить или создать новую запись в хранилище для данных, которые
     * содержатся в модели из параметра $model.
     *
     * @param \marvin255\bxar\model\ModelInterface $model
     *
     * @return bool
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function save(\marvin255\bxar\model\ModelInterface $model);

    /**
     * Пробует удалить запись в хранилище для данных, которые
     * содержатся в модели из параметра $model.
     *
     * @param \marvin255\bxar\model\ModelInterface $model
     *
     * @return bool
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function delete(\marvin255\bxar\model\ModelInterface $model);

    /**
     * Привязывает модель к репозиторию и наполняет модель данными,
     * указанными в параметре $data. Создает новую из modelName хранилища.
     *
     * @param array $data
     *
     * @return \marvin255\bxar\model\ModelInterface
     */
    public function initModel(array $data = null);

    /**
     * Приводит имена полей в единообразный вид. Используется для того,
     * чтобы не возникало ошибок при использовании
     * различных способов именований свойств: name и NAME, и т.д.
     *
     * @param string $encode
     *
     * @return string
     */
    public function encode($encode);
}
