<?php

namespace marvin255\bxar\model;

/**
 * Базовый класс для модели. Модель служит для хранения и передачи данных.
 * Любые операции, связанные с изменением записи, которая соответствует модели
 * в хранилище, следует передавать хранилищу. Соответственно, каждая модель
 * для сохранения или удаления должна быть передана хранилищу.
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
     * Передаем в модель все ее поля при ее создании. Так мы убираем лишнюю
     * ссылку на хранилище и отвязываем модель от создания каких либо классов,
     * доверяя это только самому хранилищу.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes);

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
}
