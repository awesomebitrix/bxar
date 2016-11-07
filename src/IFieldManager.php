<?php

namespace bxar;

/**
 * Интерфейс, который описывает класс, отвечающий за описание полей моделей в данном хранилище.
 */
interface IFieldManager
{
    /**
     * Возвращает массив с описанием полей модели для данного хранилища
     * ключами служат названия полей, а значениями - описания.
     *
     * @return array
     */
    public function getFieldsDescription();

    /**
     * Возвращает объект, который представляет собой
     * обработчик для конкретного поля.
     *
     * @param string $name
     *
     * @return \bxar\IField
     */
    public function getField($name);

    /**
     * Создает объект обработчика для поля модели по описанию из массива.
     *
     * @param array $description
     *
     * @return \bxar\IField
     */
    public function createField(array $description);

    /**
     * Обрабатывает название поля для того,
     * чтобы привести все названия к единообразю.
     *
     * @param string $name
     *
     * @return string
     */
    public function escapeFieldName($name);
}
