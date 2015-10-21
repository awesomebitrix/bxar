<?php

namespace bxar\element;


use \bxar\attributes\Factory;


/**
 * Active record для элементов инфоблоков
 */
class Element extends \bxar\ActiveRecord
{
	/**
	 * @var array
	 */
	protected $_initOnDemand = null;
	/**
	 * @var array
	 */
	protected $_rules = null;


	/**
	 * Возвращает массив с полями элемента инфоблока
	 * @return array
	 */
	public static function getBuiltFields()
	{
		return array(
			'ID',
			'IBLOCK_ID',
			'CODE',
			'XML_ID',
			'NAME',
			'ACTIVE',
			'DATE_ACTIVE_FROM',
			'DATE_ACTIVE_TO',
			'SORT',
			'PREVIEW_TEXT',
			'PREVIEW_TEXT_TYPE',
			'DETAIL_TEXT',
			'DETAIL_TEXT_TYPE',
			'DATE_CREATE',
			'CREATED_BY',
			'MODIFIED_BY',
			'TAGS',
			'IBLOCK_SECTION_ID',
			'DETAIL_PAGE_URL',
			'LIST_PAGE_URL',
			'DETAIL_PICTURE',
			'PREVIEW_PICTURE',
		);
	}


	/**
	 * Создает атрибуты Active Record
	 * @param array $init
	 */
	public function initAttributes(array $init)
	{
		//без указания инфоблока мы не сможем составить модель
		if (empty($init['IBLOCK_ID']) || ($iblock = $this->getIblockDescription($init['IBLOCK_ID'])) === null) {
			throw new Exception('iblock id must be valid');
		}
		//поля элемента инфоблока
		$initProperties = self::getBuiltFields();
		$init['IBLOCK_SECTION_ID'] = null;
		foreach ($initProperties as $key) {
			$this->_initOnDemand[$this->prepareAttributeName($key)] = isset($init[$key]) ? $init[$key] : null;
		}
		//свойства элемента инфоблока
		if (!empty($iblock['PROPERTIES'])) {
			foreach ($iblock['PROPERTIES'] as $property) {
				$code = !empty($property['CODE']) ? $property['CODE'] : $property['ID'];
				$preparedCode = $this->prepareAttributeName("PROPERTY_{$code}");
				$this->_initOnDemand[$preparedCode] = isset($init['PROPERTIES'][$code]['VALUE']) ? $init['PROPERTIES'][$code]['VALUE'] : null;
			}
		}
	}


	/**
	 * Возвращает массив для валидации полей модели
	 * @return array
	 */
	protected function rules()
	{
		if ($this->_rules !== null) return $this->_rules;
		if ($this->getAttribute('iblock_id') === null || $this->getAttribute('iblock_id')->getValue() === null) {
			throw new Exception('iblock id must be valid');
		}
		$iblockFields = $this->getIblockFields($this->getAttribute('iblock_id')->getValue());
		$required = array('iblock_id');
		$default = array(array('sort'), 'default', 'defaultArray' => array('sort' => 500));
		//собираем настройки валидации из настроек инфоблока
		foreach ($this->getAttributes() as $key => $attr) {
			if ($attr->getParam('is_required') === 'Y' || (isset($iblockFields[$key]) && $iblockFields[$key]['IS_REQUIRED'] === 'Y')) {
				$required[] = $key;
			}
			if ($attr->getParam('default_value') !== '' && $attr->getParam('default_value') !== null) {
				$default[0][] = $key;
				$default['defaultArray'][$key] = $attr->getParam('default_value');
			}
			if (
				isset($iblockFields[$key]['DEFAULT_VALUE'])
				&& !is_array($iblockFields[$key]['DEFAULT_VALUE'])
				&& $iblockFields[$key]['DEFAULT_VALUE'] !== ''
				&& $key !== 'sort'
			){
				$default[0][] = $key;
				$default['defaultArray'][$key] = $iblockFields[$key]['DEFAULT_VALUE'];
			}
		}
		$this->_rules = array();
		if (!empty($default[0]) && $this->isNew()) $this->_rules[] = $default;
		if (!empty($required)) $this->_rules[] = array($required, 'required');
		return $this->_rules;
	}


	/**
	 * Удаляет запись
	 * @return bool
	 */
	public function delete()
	{
		$id = $this->getAttribute('id')->getValue();
		if ($id > 0 && \CModule::IncludeModule('iblock')) {
			$res = \CIBlockElement::Delete($id);
			if ($res) {
				$this->getAttribute('id')->setValue(0);
			}
			return $res;
		} else {
			return false;
		}
	}

	/**
	 * Сохраняет запись
	 * @return bool
	 */
	public function save()
	{
		if (!\CModule::IncludeModule('iblock') || !$this->validate() || !$this->riseEvent('beforeSave')) return false;
		$ib = new \CIBlockElement;
		//собираем поля элемента
		$arFields = array();
		$arProperties = array();
		foreach ($this->getAttributes() as $key => $attr) {
			if (strpos($key, 'property_') === 0) {
				$arProperties[$attr->getParam('id')] = $attr->getValueToDb();
			} elseif ($key !== 'id') {
				$arFields[strtoupper($key)] = $attr->getValueToDb();
			}
		}
		//записываем поля элемента
		if (!$this->isNew()) {
			$id = $this->getAttribute('id')->getValue();
			//при обновлении элемента сначала записываем базовые поля
			$res = $ib->Update($id, $arFields);
			if (!$res) {
				throw new Exception($ib->LAST_ERROR);
			}
			//отдельно обновляем пользовательские свойства, чтобы не перезаписывать те, которы не были обновлены
			if (!empty($arProperties)) {
				\CIBlockElement::SetPropertyValuesEx($id, $this->getAttribute('iblock_id')->getValue(), $arProperties);
			}
			$this->riseEvent('afterSave');
		} else {
			//при вставке нового элемента записываем сразу все пользовательские свойства
			if (!empty($arProperties)) $arFields['PROPERTY_VALUES'] = $arProperties;
			$new = $ib->Add($arFields);
			if (!$new) {
				$this->getAttribute('id')->setValue($new);
				$this->riseEvent('afterSave');
			} else {
				throw new Exception($ib->LAST_ERROR);
			}
		}
		return true;
	}

	/**
	 * Есть ли данна запись в базе данных
	 * @return bool
	 */
	public function isNew()
	{
		return intval($this->getAttribute('id')->getValue()) === 0;
	}


	/**
	 * Возвращает атрибут модели по указанному имени
	 * @param string $name
	 * @return null|\bxar\IAttribute
	 */
	public function getAttribute($name)
	{
		//грузим каждый атрибут только при первом обращении к нему
		if (array_key_exists($name, $this->_initOnDemand)) {
			$init = array(
				'model' => $this,
				'code' => $name,
				'value' => $this->_initOnDemand[$name],
			);
			if (strpos($name, 'property_') === 0) {
				$code = str_replace('property_', '', $name);
				$iblockId = isset($this->_initOnDemand['iblock_id'])
				      ? $this->_initOnDemand['iblock_id']
				      : $this->getAttribute('iblock_id')->getValue();
				$iblock = $this->getIblockDescription($iblockId);
				foreach ($iblock['PROPERTIES'] as $property) {
					if ($property['ID'] === $code || $property['CODE'] === $code) {
						$init['params'] = $property;
						break;
					}
				}
			}
			$obj = $this->createAttributeFromSettings($init);
			$this->setAttribute($name, $obj);
			unset($this->_initOnDemand[$name]);
		}
		return parent::getAttribute($name);
	}

	/**
	 * Возвращает список всех имен атрибутов модели
	 * @return array
	 */
	protected function getAttributesNames()
	{
		$return = parent::getAttributesNames();
		if (!empty($this->_initOnDemand)) {
			$return = array_merge($return, array_keys($this->_initOnDemand));
		}
		return $return;
	}


	/**
	 * Создает атрибут из массива с настройками
	 * @param array $settings
	 * @return \bxar\IAttribute
	 */
	public function createAttributeFromSettings(array $settings)
	{
		if (isset($settings['params']['MULTIPLE']) && $settings['params']['MULTIPLE'] == 'Y') {
			$settings['type'] = 'multiple';
		} elseif (
			$settings['code'] == 'id'
			|| $settings['code'] == 'iblock_id'
			|| $settings['code'] == 'sort'
			|| (isset($settings['params']['PROPERTY_TYPE']) && $settings['params']['PROPERTY_TYPE'] == 'N')
		){
			$settings['type'] = 'numeric';
		} elseif (
			$settings['code'] == 'date_active_from'
			|| $settings['code'] == 'date_active_to'
			|| $settings['code'] == 'date_create'
			|| (isset($settings['params']['USER_TYPE']) && $settings['params']['USER_TYPE'] == 'DateTime')
		){
			$settings['type'] = 'date';
		} elseif ($settings['code'] == 'active') {
			$settings['type'] = 'bitrixBool';
		} elseif (isset($settings['params']['PROPERTY_TYPE']) && $settings['params']['PROPERTY_TYPE'] == 'L') {
			$settings['type'] = 'list';
		} elseif (
			$settings['code'] == 'preview_picture'
			|| $settings['code'] == 'detail_picture'
			|| (isset($settings['params']['PROPERTY_TYPE']) && $settings['params']['PROPERTY_TYPE'] == 'F')
		){
			$settings['type'] = 'file';
		} elseif ($settings['code'] == 'iblock_section_id') {
			$settings['type'] = 'bitrixSection';
		}
		return Factory::create($settings);
	}

	/**
	 * Возвращает описание инфоблока по его идентификатору
	 * @param string $iblockId
	 * @return array
	 */
	protected function getIblockDescription($iblockId)
	{
		return \bxar\helpers\Iblock::getById($iblockId);
	}

	/**
	 * Возвращает описание полей инфоблока по его идентификатору
	 * @param string $iblockId
	 * @return array
	 */
	protected function getIblockFields($iblockId)
	{
		$return = array();
		$res = \bxar\helpers\Iblock::getFields($iblockId);
		foreach ($res as $key => $value) {
			$return[$this->prepareAttributeName($key)] = $value;
		}
		return $return;
	}
}