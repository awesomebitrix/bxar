<?php

namespace bxar\helpers;

/**
 * Вспомогательные функции для работы с пользовательскими полями
 */
class Uf
{
	/**
	 * @param array
	 */
	protected static $_entities = array();


	/**
	 * Возвращает список полей, которые подключены к пользователю
	 * @return array
	 */
	public static function getListFor($entity)
	{
		if (!isset(self::$_entities[$entity])) {
			self::$_entities[$entity] = array();
			$array = array();
			$rsData = \CUserTypeEntity::GetList(array(), [
				'ENTITY_ID' => $entity,
			]);
			while ($ob = $rsData->Fetch()) {
				self::$_entities[$entity][] = $ob;
			}
		}
		return self::$_entities[$entity];
	}


	/**
	 * @param array
	 */
	protected static $_uf_enum = array();


	/**
	 * Возвращает список значений поля типа список
	 * @return array
	 */
	public static function getUserFieldEnum($entity)
	{
		if (!isset(self::$_uf_enum[$entity])) {
			self::$_uf_enum[$entity] = array();
			$array = array();
			$rsData = \CUserFieldEnum::GetList(array(), [
				'USER_FIELD_ID' => $entity,
			]);
			while ($ob = $rsData->Fetch()) {
				self::$_uf_enum[$entity][] = $ob;
			}
		}
		return self::$_uf_enum[$entity];
	}
}
