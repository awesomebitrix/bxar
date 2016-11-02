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
    public function getFields();

    /**
     * Возвращает объект, который представляет собой
     * обработчик для конкретного поля.
     *
     * @param string $name
     *
     * @return \bxar\IField
     */
    public function getField($name);
}
