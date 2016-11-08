<?php

namespace marvin255\bxar\iblock;

use UnexpectedValueException;

/**
 * Интерфей для объектов, которые реализуют связь данной библиотеки с битриксом
 */
class IblockHelper implements IIblockHelper
{
    /**
     * В конструкторе класс подгружаем модуль инфоблоков битрикса
     */
    public function __construct()
    {
        if (class_exists('\Bitrix\Main\Loader')) {
            \Bitrix\Main\Loader::includeModule('iblock');
        }
    }

    /**
     * Возвращает уникальный идентификатор инфоблока по его символьному коду
     * @param string $code
     * @return int
     * @throws UnexpectedValueException
     */
    public function findIblockIdByCode($code)
    {
        $code = trim($code);
        if ($code === '') {
            throw new UnexpectedValueException('Empty iblock code');
        }
        $res = \CIBlock::GetList(
            [],
            ['CODE' => $code]
        );
        if ($ob = $res->Fetch()) {
            return (int) $ob['ID'];
        } else {
            throw new UnexpectedValueException('Iblock doesn\'t exist: '.$code);
        }
    }

    /**
     * Возвращает описание полей инфоблока по его идентификатору
     * @param int $id
     * @return array
     */
    public function getIblockFields($id)
    {
        $id = trim($id);
        if ($id === 0) {
            throw new UnexpectedValueException('Empty iblock id');
        }
        $return = [
            'ID' => [
                'type' => 'int',
                'label' => 'Идентификатор',
            ],
            'CODE' => [
                'type' => 'string',
                'label' => 'Символьный код',
            ],
            'ACTIVE' => [
                'type' => 'bool',
                'label' => 'Активность',
            ],
            'SORT' => [
                'type' => 'int',
                'label' => 'Сортировка',
            ],
            'IBLOCK_ID' => [
                'type' => 'int',
                'label' => 'Идентификатор инфоблока',
            ],
        ];
        $res = \CIBlockProperty::GetList(
            [],
            [
                'IBLOCK_ID' => $id,
                'ACTIVE' => 'Y',
            ]
        );
        while ($ob = $res->Fetch()) {
            $key = 'property_'.(!empty($ob['CODE']) ? $ob['CODE'] : $ob['ID']);
            $return[$key] = [
                'type' => 'iblock_property',
                'label' => $ob['NAME'],
                'params' => $ob,
            ];
        }
        return $return;
    }
}
