<?php

namespace marvin255\bxar;

/**
 * Трэйт, который реализует базовые функции IModel.
 */
trait TModel
{
    /**
     * @var array
     */
    protected $_fieldsValues = [];

    /**
     * Магия для быстрого доступа к полям модели.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getFieldValue($name);
    }

    /**
     * Магия для быстрого доступа к полям модели.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->setFieldValue($name, $value);
    }

    /**
     * Магия для быстрого доступа к полям модели.
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        $fields = $this->getFieldsDescription();

        return isset($fields[$name]);
    }

    /**
     * Возвращает массив всех значений полей модели.
     *
     * @return array
     */
    public function getFieldsValues()
    {
        $fields = $this->getFieldsDescription();
        $names = array_keys($fields);
        $return = [];
        foreach ($names as $name) {
            $return[$name] = $this->getFieldValue($name);
        }

        return $return;
    }

    /**
     * Задает массив со всеми значениями полей модели.
     *
     * @param array $values
     *
     * @return \marvin255\bxar\IModel
     */
    public function setFieldsValues(array $values)
    {
        foreach ($values as $name => $value) {
            $this->setFieldValue($name, $value);
        }

        return $this;
    }

    /**
     * Получает значение поля модели.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getFieldValue($name)
    {
        $field = $this->getField($name);

        return $field->getFieldValueFromModel();
    }

    /**
     * Задает значение поля модели.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return \marvin255\bxar\IModel
     */
    public function setFieldValue($name, $value)
    {
        $field = $this->getField($name);
        $this->_fieldsValues[$field->getName()] = $field->getFieldValueFromModel();

        return $this;
    }

    /**
     * Возвращает объект для обработки данного типа поля.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return \marvin255\bxar\IField
     */
    public function getField($name)
    {
        $field = $this->getRepo()->getField($name);
        $field->setFieldValueFromModel(
            isset($this->_fieldsValues[$field->getName()])
                ? $this->_fieldsValues[$field->getName()]
                : null
        );

        return $field;
    }

    /**
     * Возвращает список полей с описаниями для данной модели.
     *
     * @return array
     */
    public function getFieldsDescription()
    {
        return $this->getRepo()->getFieldsDescription();
    }

    /**
     * @var \marvin255\bxar\IRepo
     */
    protected $_repo = null;

    /**
     * Задает объект базового хранилища для данной модели.
     *
     * @param \marvin255\bxar\IRepo $repo
     *
     * @return \marvin255\bxar\IModel
     */
    public function setRepo(IRepo $repo)
    {
        $this->_repo = $repo;
    }

    /**
     * Возвращает объект базового хранилища для данной модели.
     *
     * @return \marvin255\bxar\IRepo
     */
    public function getRepo()
    {
        return $this->_repo;
    }
}
