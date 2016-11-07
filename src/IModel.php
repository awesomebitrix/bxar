<?php

namespace marvin255\bxar;

/**
 * Интерфейс, который описывает класс модели, в которой будут храниться данные,
 * возвращаемые хранилищем
 */
interface IModel
{
    /**
     * Возвращает массив всех значений полей модели.
     *
     * @return array
     */
    public function getFieldsValues();

    /**
     * Задает массив со всеми значениями полей модели.
     *
     * @param array $values
     *
     * @return \marvin255\bxar\IModel
     */
    public function setFieldsValues(array $values);

    /**
     * Получает значение поля модели.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getFieldValue($name);

    /**
     * Задает значение поля модели.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return \marvin255\bxar\IModel
     */
    public function setFieldValue($name, $value);

    /**
     * Возвращает объект для обработки данного типа поля.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return \marvin255\bxar\Field
     */
    public function getField($name);

    /**
     * Возвращает список полей с описаниями для данной модели.
     *
     * @return array
     */
    public function getFieldsDescription();

    /**
     * Задает объект базового хранилища для данной модели.
     *
     * @param \marvin255\bxar\IRepo $repo
     *
     * @return \marvin255\bxar\IModel
     */
    public function setRepo(IRepo $repo);

    /**
     * Возвращает объект базового хранилища для данной модели.
     *
     * @return \marvin255\bxar\IRepo
     */
    public function getRepo();
}
