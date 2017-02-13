<?php

namespace marvin255\bxar\model;

/**
 * Базовый класс для атрибута модели. Хранит в себе данные, которые соотвествуют
 * определенному столбцу в хранилище для указанной модели. Кроме того, хранит в себе
 * ошибки, связанные с валидацией данного поля, а так же все возможные преобразования
 * значения или дополнительные методы, облегчающие работу с данным столбцом.
 */
interface FieldInterface
{
    /**
     * В конструкторе задаем название поля и привязку к хранилищу, чтобы
     * избежать возможных ошибок с изменением названия или привязки, разрешаем
     * задавать их исключительно через конструктор.
     *
     * @param string                             $name
     * @param \marvin255\bxar\repo\RepoInterface $repo
     */
    public function __construct($name, \marvin255\bxar\repo\RepoInterface $repo);

    /**
     * Возвращает название для данного атрибута.
     *
     * @return string
     */
    public function getName();

    /**
     * Возвращает хранилище, к которому привязан данный атрибут.
     *
     * @return \marvin255\bxar\model\ModelInterface
     */
    public function getRepo();

    /**
     * Задает значение атрибута.
     *
     * @param mixed $value
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function setValue($value);

    /**
     * Возвращает значение атрибута.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Возвращает список параметров данного атрибута.
     *
     * @return array
     */
    public function getParams();

    /**
     * Возвращает значение параметра атрибута, указанного в $name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParam($name);

    /**
     * Добавляет ошибку к списку ошибок данного атрибута.
     *
     * @param string $error
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function addError($error);

    /**
     * Возвращает все ошибки, которые установлены для данного атрибута или null.
     *
     * @return array|null
     */
    public function getErrors();

    /**
     * Очищает список ошибок для данного атрибута.
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function clearErrors();
}
