<?php

namespace bxar;

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
     * @return \bxar\IModel
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
     * @return \bxar\IModel
     */
    public function setFieldValue($name, $value);

    /**
     * Возвращает объект для обработки данного типа поля.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return \bxar\Field
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
     * @param \bxar\IRepo $repo
     *
     * @return \bxar\IModel
     */
    public function setRepo(IRepo $repo);

    /**
     * Возвращает объект базового хранилища для данной модели.
     *
     * @return \bxar\IRepo
     */
    public function getRepo();
}
