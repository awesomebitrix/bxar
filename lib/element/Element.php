<?php

namespace bx\ar\element;


use \bx\ar\attributes\Factory;


/**
 * Active record для элементов инфоблоков
 */
class Element extends \bx\ar\ActiveRecord
{
	/**
	 * @var array список полей инфоблоков для инициации записей
	 */
	protected static $_propertiesDescriptions = array();
	/**
	 * @var array информация о валидации для свойств инфоблока
	 */
	protected $_propertiesValidation = null;


	/**
	 * Создает объект записи для указанного инфоблока
	 * @param int $iblock
	 * @param string $scenario
	 * @return \bx\ar\ActiveRecord
	 */
	public static function initByIblock($iblock, $scenario = 'default')
	{
		$ar = new self($scenario);
		$ar->initAttributes(array('IBLOCK_ID' => $iblock), true);
		return $ar;
	}
	


	/**
	 * Создает объект для поиска нужных записей
	 * @param array $filter
	 * @param \bx\ar\IFinder $finder
	 * @return \bx\ar\IFinder
	 */
	public static function find(array $filter = null, \bx\ar\IFinder $finder = null)
	{
		$finder = new \bx\ar\element\Finder;
		return parent::find($filter, $finder);
	}


	/**
	 * Удаляет инфоблок
	 * @return bool
	 */
	public function delete()
	{
		$id = $this->getAttribute('ID')->getValue();
		if ($id !== null && \CModule::IncludeModule('iblock')) {
			$res = \CIBlockElement::Delete($id);
			if ($res) {
				$this->getAttribute('ID')->setValue(null);
			}
			return $res;
		} else {
			return false;
		}
	}

	/**
	 * Сохраняет запись
	 */
	public function save()
	{
		if (!\CModule::IncludeModule('iblock') || !$this->validate()) return false;
		$id = $this->getAttribute('ID')->getValue();
		$ib = new \CIBlockElement;
		$arFields = array();
		foreach ($this->getAttributes() as $attr) {
			$params = $attr->getParams();
			if (!empty($params['ID'])) {
				$arFields['PROPERTY_VALUES'][$params['ID']] = $attr->getValueToDb();
			} else {
				$arFields[$attr->getCode()] = $attr->getValueToDb();
			}
		}
		if ($id) {
			$res = $ib->Update($id, $arFields);
			if ($res) {
				return true;
			} else {
				throw new Exception($ib->LAST_ERROR);
			}
		} else {
			$new = $ib->Add($arFields);
			if ($new) {
				$this->getAttribute('ID')->setValue($new);
				return true;
			} else {
				throw new Exception($ib->LAST_ERROR);
			}
		}
	}


	/**
	 * Возвращает массив для валидации полей модели
	 * @return array
	 */
	protected function rules()
	{
		$return = array(
			array(array('CODE', 'XML_ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT'), 'filter', 'filter' => 'trim'),
			array('SORT', 'default', 'value' => 500),
			array(array('SORT'), 'filter', 'filter' => 'intval'),
			array(array('TIMESTAMP_X'), 'date', 'currentIfNull' => true, 'toFormat' => 'FULL'),
			array(array('ACTIVE'), 'default', 'value' => 'Y'),
			array(array('ACTIVE'), 'bxBool'),
			array(array('PREVIEW_TEXT_TYPE', 'DETAIL_TEXT_TYPE'), 'default', 'value' => 'text'),
			array(array('IBLOCK_ID', 'NAME', 'ACTIVE', 'PREVIEW_TEXT_TYPE', 'DETAIL_TEXT_TYPE'), 'required'),
		);

		return array_merge($return, $this->getPropertiesValidation());
	}

	/**
	 * Возвращает настройки валидации свойств инфоблока
	 * @return array
	 */
	protected function getPropertiesValidation()
	{
		if ($this->_propertiesValidation === null) {
			$this->_propertiesValidation = array();
			$required = null;
			$trim = null;
			$float = null;
			$safe = null;
			foreach ($this->getAttributes() as $attribute) {
				$params = $attribute->getParams();
				if (!empty($params['IS_REQUIRED']) && $params['IS_REQUIRED'] == 'Y') {
					$required[] = $attribute->getCode();
				}
				if (!empty($params['PROPERTY_TYPE']) && $params['PROPERTY_TYPE'] == 'S') {
					$trim[] = $attribute->getCode();
				} elseif (!empty($params['PROPERTY_TYPE']) && $params['PROPERTY_TYPE'] == 'N') {
					$float[] = $attribute->getCode();
				} elseif (!empty($params['PROPERTY_TYPE']) && $params['PROPERTY_TYPE'] == 'L') {
					$this->_propertiesValidation[] = array($attribute->getCode(), 'in', 'range' => $attribute->getList('id'));
				} else {
					$safe[] = $attribute->getCode();
				}
			}
			if ($required) $this->_propertiesValidation[] = array($required, 'required');
			if ($trim) $this->_propertiesValidation[] = array($trim, 'filter', 'trim');
			if ($float) $this->_propertiesValidation[] = array($float, 'filter', 'floatval');
			if ($safe) $this->_propertiesValidation[] = array($safe, 'safe');
		}
		return $this->_propertiesValidation;
	}

	/**
	 * Создает атрибуты Active Record
	 * @param array $init
	 */
	public function initAttributes(array $init, $force = false)
	{
		//получаем описание свойств инфоблока, если они не указаны в массиве для инициации
		if (empty($init['IBLOCK_ID'])) {
			throw new \bx\ar\Exception('IBLOCK_ID must be specified in init array.');
		}
		if (!isset($init['PROPERTIES'])) {
			if (!isset(self::$_propertiesDescriptions[$init['IBLOCK_ID']]) && \CModule::IncludeModule('iblock')) {
				self::$_propertiesDescriptions[$init['IBLOCK_ID']] = array();
				$res = \CIBlockProperty::GetList(array(), array('IBLOCK_ID' => $init['IBLOCK_ID']));
				while ($ob = $res->Fetch()) {
					self::$_propertiesDescriptions[$init['IBLOCK_ID']][] = $ob;
				}
			}
			$init['PROPERTIES'] = self::$_propertiesDescriptions[$init['IBLOCK_ID']];
		}
		return parent::initAttributes($init, $force);
	}

	/**
	 * Возвращает массив с описание атрибутов для данного типа записей
	 * @param mixed $init
	 * @return array
	 */
	protected function getAttributesDescriptions($init = null)
	{
		$return = Factory::createFromArray(array(
			'ID' => array(),
			'IBLOCK_ID' => array(),
			'CODE' => array(),
			'XML_ID' => array(),
			'NAME' => array(),
			'ACTIVE' => array(),
			'DATE_ACTIVE_FROM' => array(),
			'DATE_ACTIVE_TO' => array(),
			'SORT' => array(),
			'PREVIEW_TEXT' => array(),
			'PREVIEW_TEXT_TYPE' => array(),
			'DETAIL_TEXT' => array(),
			'DETAIL_TEXT_TYPE' => array(),
			'DATE_CREATE' => array(),
			'CREATED_BY' => array(),
			'TIMESTAMP_X' => array(),
			'MODIFIED_BY' => array(),
			'TAGS' => array(),
			'DETAIL_PICTURE' => array(),
			'PREVIEW_PICTURE' => array(),
		));

		if (!empty($init['PROPERTIES']) && is_array($init['PROPERTIES'])) {
			foreach ($init['PROPERTIES'] as $key => $val) {
				if (empty($val['ID']) || !isset($val['PROPERTY_TYPE'])) continue;
				$code = !empty($val['CODE']) ? 'PROPERTY_' . strtoupper($val['CODE']) : 'PROPERTY_' . $val['ID'];
				$value = $val['~VALUE'];
				unset($val['~VALUE'], $val['VALUE']);
				$type = $val['MULTIPLE'] == 'Y' ? 'property_multiple' : 'property_' . strtolower($val['PROPERTY_TYPE']);
				$return[$code] = Factory::create($code, $type, $val);
				$return[$code]->setValue($value);
			}
		}

		return $return;
	}
}