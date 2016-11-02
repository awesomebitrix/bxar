<?php

namespace bxar;

use UnexpectedValueException;
use InvalidArgumentException;

/**
 * Трэйт, который реализует базовые функции IModel
 */
interface TModel
{
    /**
     * @var array
     */
    protected $_fieldsValues = [];

    /**
     * Магия для быстрого доступа к полям модели
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
     * Магия для быстрого доступа к полям модели
     *
     * @param string $name
     * @param mixed $value
     *
     * @return null
     */
    public function __set($name, $value)
    {
        $this->setFieldValue($name, $value);
    }

    /**
     * Магия для быстрого доступа к полям модели
     *
     * @param string $name
     * @return bool
     *
     */
    public function __isset($name)
    {
        $fields = $this->getFieldsDescription();

        return isset($fields[$name]);
    }

    /**
     * Возвращает массив всех значений полей модели
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
     * Задает массив со всеми значениями полей модели
     *
     * @param array $values
     *
     * @return \bxar\IModel
     */
    public function setFieldsValues(array $values)
    {
        foreach ($values as $name => $value) {
            $this->setFieldValue($name, $value);
        }

        return $this;
    }

    /**
     * Получает значение поля модели
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
     * Задает значение поля модели
     *
     * @param string $name
     * @param mixed $value
     *
     * @return \bxar\IModel
     */
    public function setFieldValue($name, $value)
    {
        $field = $this->getField($name);
        $this->_fieldsValues[$field->getName()] = $field->getFieldValueFromModel();

        return $this;
    }

    /**
     * Возвращает объект для обработки данного типа поля
     *
     * @param string $name
     * @param mixed $value
     *
     * @return \bxar\Field
     */
    public function getField($name)
    {
        $field = $this->getFieldManager()->getField($name);
        $field->setFieldValueFromModel(
            isset($this->_fieldsValues[$field->getName()])
                ? $this->_fieldsValues[$field->getName()]
                : null
        );

        return $field;
    }

    /**
     * Возвращает список полей с описаниями для данной модели
     *
     * @return array
     */
    public function getFieldsDescription()
    {
        $fieldManager = $this->getFieldManager();

        return $fieldManager->getFields();
    }

    /**
     * Возвращает объект менеджера полей
     *
     * @return \bxar\IFieldManager
     *
     * @throws \UnexpectedValueException
     */
    protected function getFieldManager()
    {
        $repo = $this->getRepo();
        if (empty($repo)) {
            throw UnexpectedValueException('Repo can not be empty');
        }
        $fieldManager = $repo->getFieldManager();
        if (empty($fieldManager)) {
            throw UnexpectedValueException('Field manager can not be empty');
        }

        return $fieldManager;
    }

    /**
     * @var \bxar\IRepo
     */
    protected $_repo = null;

    /**
     * Задает объект базового хранилища для данной модели
     *
     * @param \bxar\IRepo $repo
     *
     * @return \bxar\IModel
     */
    public function setRepo(IRepo $repo)
    {
        $this->_repo = $repo;
    }

    /**
     * Возвращает объект базового хранилища для данной модели
     *
     * @return \bxar\IRepo
     */
    public function getRepo()
    {
        return $this->_repo;
    }
}
