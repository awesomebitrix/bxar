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
}