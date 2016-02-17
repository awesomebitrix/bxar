<?php

namespace bxar\helpers;

use Bitrix\Main\Loader;

Loader::includeModule('highloadblock');

/**
 * Вспомогательные функции для работы c highload инфоблоками
 */
class HlEntity
{
	/**
	 * @var array
	 */
	protected static $_compiled = [];
	/**
	 * @var array
	 */
	protected static $_entities = [];

	/**
	 * Возвращает класс для указанной сущности
	 * @param string $entity
	 * @return string
	 */
	public static function compile($entity)
	{
		$entity = trim($entity);
		if ($entity === '') return null;
		if (!array_key_exists($entity, self::$_compiled[$entity])) {
			if (class_exists($entity)) {
				self::$_compiled[$entity] = $entity;
			} else {
				//проверяем существует ли сущность
				$hlblock = self::getEntityByName($entity);
				if (!empty($hlblock['ID'])) {
					$entityObj = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
					self::$_compiled[$entity] = $entityObj->getDataClass();
				} else {
					self::$_compiled[$entity] = null;					
				}
			}
		}
		return self::$_compiled[$entity];
	}

	/**
	 * Возвращает список полей для сущности
	 * @param string $entity
	 * @return array
	 */
	public function getFields($entity)
	{
		$return = null;
		$hlblock = self::getEntityByName($entity);
		if (!empty($hlblock['ID'])) {
			$return = \bxar\helpers\Uf::getListFor("HLBLOCK_{$hlblock['ID']}");
		}
		return $return;
	}

	/**
	 * Ищет данные о сущности по ее названию
	 * @param string $name
	 * @return array
	 */
	public static function getEntityByName($name)
	{
		if (!array_key_exists($name, self::$_entities[$name])) {
			$filter = [
				'select' => ['ID', 'NAME', 'TABLE_NAME'],
				'filter' => ['NAME' => $name],
			];
			$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList($filter)->fetch();
			if (!empty($hlblock['ID'])) {
				self::$_entities[$name] = $hlblock;
			} else {
				self::$_entities[$name] = null;
			}
		}
		return self::$_entities[$name];
	}
}