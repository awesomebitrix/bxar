<?php

namespace marvin255\bxar\iblock;

/**
 * Интерфейс для объектов, которые реализуют связь данной библиотеки с битриксом
 */
interface IIblockHelper
{
    /**
     * Возвращает уникальный идентификатор инфоблока по его символьному коду
     * @param string $code
     * @return int
     */
    public function findIblockIdByCode($code);

    /**
     * Возвращает описание полей инфоблока по его идентификатору
     * @param int $id
     * @return array
     */
    public function getIblockFields($id);
}
