<?php

namespace bxar;

/**
 * Трэйт, который реализует базовые функции IField
 */
trait TField
{
    /**
     * @var mixed
     */
    protected $_fieldValueFromModel = null;

    /**
     * Задает значение, которое будет обработано для вывода
     *
     * @param mixed $value
     *
     * @return \bxar\IField
     */
    public function setFieldValueFromModel($value)
    {
        $this->_fieldValueFromModel = $value;
    }

    /**
     * Возвращает значение, обработанное для вывода
     *
     * @return mixed
     */
    public function getFieldValueFromModel()
    {
        return $this->_fieldValueFromModel;
    }

    /**
     * Возвращает значение, обработанное для записи в репозиторий
     *
     * @return mixed
     */
    public function getFieldValueFromModelToRepo()
    {
        return $this->_fieldValueFromModel;
    }

    /**
     * Сбрасывает все настройки данного поля
     *
     * @return \bxar\IField
     */
    public function clearField()
    {
        $this->_fieldValueFromModel = null;
    }
}
