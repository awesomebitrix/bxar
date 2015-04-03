<?php

namespace bx\ar\iblock;


use \bx\ar\attributes\Factory;


/**
 * Active record для инфоблоков
 */
class Iblock extends \bx\ar\ActiveRecord
{
	/**
	 * Шорткат для поиска инфоблока по его коду
	 * @param string $code
	 * @return \bx\ar\iblock\Ar|null
	 */
	public static function findByCode($code)
	{
		return self::find(array('CODE' => trim($code)))->one();
	}

	/**
	 * Шорткат для поиска инфоблока по его id
	 * @param int $id
	 * @return \bx\ar\iblock\Ar|null
	 */
	public static function findById($id)
	{
		return self::find(array('ID' => intval($code)))->one();
	}

	/**
	 * Шорткат, который возвращает id инфоблока по его коду
	 * @param string $code
	 * @param int $cache
	 * @return int
	 */
	public static function findIdByCode($code, $cache = 0)
	{
		$id = 0;
		$iblock = self::find(array('CODE' => trim($code)))->cache($cache)->setAsArray()->one();
		if (!empty($iblock)) $id = (int) $iblock['ID'];
		return $id;
	}


	/**
	 * Возвращает массив для валидации полей модели
	 * @return array
	 */
	protected function rules()
	{
		return array(
			array(array('ID', 'SORT', 'RSS_TTL', 'RSS_FILE_LIMIT', 'RSS_FILE_DAYS', 'VERSION'), 'filter', 'filter' => 'intval'),
			array(array('TIMESTAMP_X'), 'date', 'currentIfNull' => true, 'toFormat' => 'FULL'),
			array(array('ACTIVE', 'RSS_ACTIVE', 'INDEX_ELEMENT', 'WORKFLOW'), 'default', 'value' => 'Y'),
			array(array('RSS_FILE_ACTIVE', 'RSS_YANDEX_ACTIVE', 'INDEX_SECTION'), 'default', 'value' => 'N'),
			array('SORT', 'default', 'value' => 500),
			array('RSS_TTL', 'default', 'value' => 24),
			array('VERSION', 'default', 'value' => 1),
			array('DESCRIPTION_TYPE', 'default', 'value' => 'text'),
			array(
				array(
					'ACTIVE',
					'RSS_ACTIVE',
					'RSS_FILE_ACTIVE',
					'RSS_YANDEX_ACTIVE',
					'INDEX_ELEMENT',
					'INDEX_SECTION',
					'WORKFLOW',
					'SECTION_CHOOSER',
				),
				'bxBool'
			),
			array(
				array(
					'ID',
					'IBLOCK_TYPE_ID',
					'TIMESTAMP_X',
					'NAME',
					'ACTIVE',
					'SORT',
					'DESCRIPTION_TYPE',
					'RSS_ACTIVE',
					'RSS_TTL',
					'RSS_FILE_ACTIVE',
					'RSS_YANDEX_ACTIVE',
					'INDEX_ELEMENT',
					'INDEX_SECTION',
					'WORKFLOW',
					'VERSION',
				),
				'required'
			),
		);
	}

	/**
	 * Возвращает массив с описание атрибутов для данного типа записей
	 * @return array
	 */
	protected function getAttributesDescriptions()
	{
		return Factory::createFromArray(array(
			'ID' => array(),
			'TIMESTAMP_X' => array(),
			'IBLOCK_TYPE_ID' => array(),
			'LID' => array(),
			'CODE' => array(),
			'NAME' => array(),
			'ACTIVE' => array(),
			'SORT' => array(),
			'LIST_PAGE_URL' => array(),
			'DETAIL_PAGE_URL' => array(),
			'SECTION_PAGE_URL' => array(),
			'PICTURE' => array(),
			'DESCRIPTION' => array(),
			'DESCRIPTION_TYPE' => array(),
			'RSS_TTL' => array(),
			'RSS_ACTIVE' => array(),
			'RSS_FILE_ACTIVE' => array(),
			'RSS_FILE_LIMIT' => array(),
			'RSS_FILE_DAYS' => array(),
			'RSS_YANDEX_ACTIVE' => array(),
			'XML_ID' => array(),
			'TMP_ID' => array(),
			'INDEX_ELEMENT' => array(),
			'INDEX_SECTION' => array(),
			'WORKFLOW' => array(),
			'BIZPROC' => array(),
			'SECTION_CHOOSER' => array(),
			'LIST_MODE' => array(),
			'RIGHTS_MODE' => array(),
			'SECTION_PROPERTY' => array(),
			'PROPERTY_INDEX' => array(),
			'VERSION' => array(),
			'LAST_CONV_ELEMENT' => array(),
			'SOCNET_GROUP_ID' => array(),
			'EDIT_FILE_BEFORE' => array(),
			'EDIT_FILE_AFTER' => array(),
			'SECTIONS_NAME' => array(),
			'SECTION_NAME' => array(),
			'ELEMENTS_NAME' => array(),
			'ELEMENT_NAME' => array(),
			'LANG_DIR' => array(),
			'SERVER_NAME' => array(),
			'EXTERNAL_ID' => array(),
		));
	}
}