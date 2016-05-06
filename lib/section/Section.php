<?php

namespace bxar\section;


use \bxar\attributes\Factory;


/**
 * Active record для разделов инфоблоков
 */
class Section extends \bxar\ActiveRecord
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
	 * @var \marvin255\bxlib\IblockLocator
	 */
	protected $_iblockLocator = null;


	/**
	 * Возвращает массив с полями элемента инфоблока
	 * @return array
	 */
	public static function getBuiltFields()
	{
		return array(
			'ID',
			'CODE',
			'XML_ID',
			'IBLOCK_ID',
			'IBLOCK_SECTION_ID',
			'SORT',
			'NAME',
			'ACTIVE',
			'GLOBAL_ACTIVE',
			'PICTURE',
			'DESCRIPTION',
			'DESCRIPTION_TYPE',
			'LEFT_MARGIN',
			'RIGHT_MARGIN',
			'DEPTH_LEVEL',
			'SECTION_PAGE_URL',
			'MODIFIED_BY',
			'DATE_CREATE',
			'CREATED_BY',
			'DETAIL_PICTURE',
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
		foreach ($initProperties as $key) {
			$this->_initOnDemand[$this->prepareAttributeName($key)] = isset($init[$key]) ? $init[$key] : null;
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
		$required = array('iblock_id', 'name');
		$default = array(array('sort'), 'default', 'defaultArray' => array('sort' => 500));
		//собираем настройки валидации из настроек инфоблока
		/*foreach ($this->getAttributes() as $key => $attr) {
			if ($attr->getParam('is_required') === 'Y' || (isset($iblockFields[$key]) && $iblockFields[$key]['IS_REQUIRED'] === 'Y')) {
				$required[] = $key;
			}
			if ($attr->getParam('property_type') === 'N') {
				$toFloat[] = $key;
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
		}*/
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
			$res = \CIBlockSection::Delete($id);
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
		$ib = new \CIBlockSection;
		//собираем поля элемента
		$arFields = array();
		$arProperties = array();
		foreach ($this->getAttributes() as $key => $attr) {
			$arFields[strtoupper($key)] = $attr->getValueToDb();
		}
		//записываем поля элемента
		if (!$this->isNew()) {
			$id = $this->getAttribute('id')->getValue();
			//при обновлении элемента сначала записываем базовые поля
			$res = $ib->Update($id, $arFields);
			if (!$res) {
				throw new Exception($ib->LAST_ERROR);
			}
			$this->riseEvent('afterSave');
		} else {
			//при вставке нового элемента записываем сразу все пользовательские свойства
			$new = $ib->Add($arFields);
			if ($new) {
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
		$name = $this->prepareAttributeName($name);
		//грузим каждый атрибут только при первом обращении к нему
		if (array_key_exists($name, $this->_initOnDemand)) {
			$init = array(
				'model' => $this,
				'code' => $name,
				'value' => $this->_initOnDemand[$name],
			);
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
		if (
			$settings['code'] == 'id'
			|| $settings['code'] == 'iblock_id'
			|| $settings['code'] == 'sort'
			|| $settings['code'] == 'left_margin'
			|| $settings['code'] == 'right_margin'
			|| $settings['code'] == 'depth_level'
		){
			$settings['type'] = 'numeric';
		} elseif (
			$settings['code'] == 'date_create'
		){
			$settings['type'] = 'date';
		} elseif (
			$settings['code'] == 'active'
			|| $settings['code'] == 'global_active'
		){
			$settings['type'] = 'bitrixBool';
		}  elseif (
			$settings['code'] == 'picture'
			|| $settings['code'] == 'detail_picture'
		){
			$settings['type'] = 'file';
		} elseif ($settings['code'] == 'iblock_section_id') {
			$settings['type'] = 'numeric';
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
		$locator = $this->getIblockLocator();
		if ($locator) {
			return $locator->findBy('ID', $iblockId);
		} else {
			return \bxar\helpers\Iblock::getById($iblockId);
		}
	}


	/**
	 * @return \marvin255\bxlib\IblockLocator
	 */
	public function getIblockLocator()
	{
		return $this->_iblockLocator;
	}

	/**
	 * @param \marvin255\bxlib\IblockLocator $locator
	 */
	public function setIblockLocator(\marvin255\bxlib\IblockLocator $locator)
	{
		$this->_iblockLocator = $locator;
		return $this;
	}
}
