<?php

namespace marvin255\bxar\model;

/**
 * Базовый класс для модели. Модель служит для хранения и передачи данных.
 * Любые операции, связанные с изменением записи, которая соответствует модели
 * в хранилище, следует передавать хранилищу. Соответственно,
 * каждая модель должна быть привязана к своему хранилищу.
 */
interface ModelInterface
{
    /**
     * Магия для быстрого получения объекта-обработчика атрибута.
     *
     * @param string $name
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function __get($name);

    /**
     * Привязываем модель к репозиторию в конструкторе, при этом запрещаем менять
     * ссылку на репозиторий во время жизни модели, чтобы избежать возможных ошибок.
     *
     * @param \marvin255\bxar\repo\RepoInterface $repo
     */
    public function __construct(\marvin255\bxar\repo\RepoInterface $repo);

    /**
     * Возвращает ссылку на хранилище, к которому привязана модель.
     *
     * @return \marvin255\bxar\repo\RepoInterface
     */
    public function getRepo();

    /**
     * Возвращает объект-обработчик для атрибута модели, указанного в $name.
     * Если объект атрибута еще не создан, то обращается к хранилищу, чтобы
     * оно предоставило новый объект для атрибута.
     *
     * @param string $name
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function getAttribute($name);

    /**
     * Наполняет атрибуты модели данными.
     *
     * @param array $attributes
     */
    public function setAttributesValues(array $attributes);

    /**
     * Возвращает значения всех атрибутов модели в виде массива, в котором ключом
     * служит название атрибута, а значением - его значение.
     *
     * @return array
     */
    public function getAttributesValues();

    /**
     * Возвращает значения всех ошибок в атрибутах модели в виде массива, в котором ключом
     * служит название атрибута, а значением - список ошибок.
     *
     * @return array
     */
    public function getAttributesErrors();

    /**
     * Пробует сохранить данные модели в хранилище. Шорткат для вызова save хранилища.
     *
     * @return bool
     */
    public function save();

    /**
     * Пробует удалить данные модели из хранилища. Шорткат для вызова delete хранилища.
     *
     * @return bool
     */
    public function delete();
}
