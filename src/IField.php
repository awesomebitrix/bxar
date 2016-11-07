<?php

namespace marvin255\bxar;

/**
 * Интерфейс, который описывает класс,
 * отвечающий за обработку конкретного типа поля.
 */
interface IField
{
    /**
     * Задает значение, которое будет обработано для вывода.
     *
     * @param mixed $value
     *
     * @return \marvin255\bxar\IField
     */
    public function setFieldValueFromModel($value);

    /**
     * Возвращает значение, обработанное для вывода.
     *
     * @return mixed
     */
    public function getFieldValueFromModel();

    /**
     * Возвращает значение, обработанное для записи в репозиторий.
     *
     * @return mixed
     */
    public function getFieldValueFromModelToRepo();

    /**
     * Сбрасывает все настройки данного поля.
     *
     * @return \marvin255\bxar\IField
     */
    public function clearField();
}
