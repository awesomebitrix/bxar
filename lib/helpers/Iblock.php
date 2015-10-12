<?php

namespace bx\ar\helpers;

/**
 * Вспомогательные функции для работы с инфоблоками
 */
class Iblock
{
	/**
	 * Возвращает информацию об инфоблоке по его id
	 * @param int $id
	 * @return array
	 */
	public static function getById($id)
	{
		$id = trim($id);
		$return = null;
		$list = self::getList();
		foreach ($list as $iblock) {
			if ($iblock['ID'] === $id) {
				$return = $iblock;
				break;
			}
		}
		if ($return === null) {
			$return = self::findBy(array('ID' => $id));
		}
		return $return;
	}

	/**
	 * Возвращает информацию об инфоблоке по его коду
	 * @param string $code
	 * @return array
	 */
	public static function getByCode($code)
	{
		$code = trim($code);
		$return = null;
		$list = self::getList();
		foreach ($list as $iblock) {
			if ($iblock['CODE'] === $code) {
				$return = $iblock;
				break;
			}
		}
		if ($return === null) {
			$return = self::findBy(array('CODE' => $code));
		}
		return $return;
	}

	/**
	 * Возвращает id инфоблока по его коду
	 * @param string $code
	 * @return string
	 */
	public static function getIdByCode($code)
	{
		$iblock = self::getByCode($code);
		return isset($iblock['ID']) ? $iblock['ID'] : null;
	}


	/**
	 * @var array
	 */
	protected static $_list = null;

	/**
	 * Возвращает список всех инфоблоков, которые сохранены в памяти
	 * @return array
	 */
	protected static function getList()
	{
		return self::$_list;
	}

	/**
	 * Ищент инфоблок по указанному фильтру и добавляет его в общий список
	 * @param array $filter
	 * @return array
	 */
	protected static function findBy(array $filter)
	{
		if (!\CModule::IncludeModule('iblock')) return null;
		$res = \CIBlock::GetList(
			array(),
			$filter,
			false
		);
		if ($ob = $res->GetNext()) {
			$iblock = array();
			foreach ($ob as $key => $value) {
				if (strpos($key, '~') !== 0) continue;
				$iblock[substr($key, 1)] = $value;
			}
			$pRes = \CIBlockProperty::GetList(
				array(),
				array('IBLOCK_ID' => $ob['ID'])
			);
			while ($pOb = $pRes->GetNext()) {
				$property = array();
				foreach ($pOb as $key => $value) {
					if (strpos($key, '~') !== 0) continue;
					$property[substr($key, 1)] = $value;
				}
				$iblock['PROPERTIES'][] = $property;
			}
			self::$_list[] = $iblock;
			return $iblock;
		}
		return null;
	}


	/**
	 * @var array
	 */
	protected static $_fields = array();

	/**
	 * Возвращает описание полей инфоблока
	 * @return array
	 */
	public static function getFields($iblockId)
	{
		if (!\CModule::IncludeModule('iblock')) return null;
		if (!isset(self::$_fields[$iblockId])) {
			self::$_fields[$iblockId] = \CIBlock::getFields($iblockId);
		}
		return self::$_fields[$iblockId];
	}
}