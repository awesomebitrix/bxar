<?php

namespace bx\ar\helpers;

/**
 * Вспомогательные функции для работы со списками инфоблоков
 */
class Enum
{
	/**
	 * @var array
	 */
	protected static $_items = array();

	/**
	 * Возвращает список всех вариантов для указанного свойства
	 * @param int $propertyId
	 * @return array
	 */
	public static function getById($id)
	{
		if (!isset(self::$_items[$id])) {
			if (!\CModule::IncludeModule('iblock')) throw new \bx\ar\Exception('Iblock module is not installed');
			self::$_items[$id] = array();
			$res = \CIBlockPropertyEnum::GetList(
				array('SORT' => 'ASC', 'VALUE' => 'ASC'),
				array('PROPERTY_ID' => $id)
			);
			while ($ob = $res->GetNext()) {
				self::$_items[$id][] = $ob;
			}
		}
		return self::$_items[$id];
	}
}