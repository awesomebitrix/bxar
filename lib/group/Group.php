<?php

namespace bxar\group;


use \bxar\attributes\Factory;


/**
 * Active record для групп пользователей
 */
class Group extends \bxar\ActiveRecord
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
			'C_SORT',
			'ANONYMOUS',
			'ACTIVE',
			'NAME',
			'DESCRIPTION',
			'STRING_ID',
			'REFERENCE_ID',
			'REFERENCE',
			'TIMESTAMP_X',
		);
	}


	/**
	 * Создает атрибуты Active Record
	 * @param array $init
	 */
	public function initAttributes(array $init)
	{
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

		$required = array('name');
		$default = array(
			array('active'),
			'default',
			'defaultArray' => array('active' => 'Y')
		);

		$this->_rules = array();
		if (!empty($default[0]) && $this->isNew()) $this->_rules[] = $default;
		if (!empty($required)) $this->_rules[] = array($required, 'required');
		return $this->_rules;
	}


	/**
	 * Удаляет группу
	 * @return bool
	 */
	public function delete()
	{
		$id = $this->getAttribute('id')->getValue();
		if ($id > 0) {
			$group = new CGroup;
			$DB->StartTransaction();
			if (!$group->Delete($id)) {
				$DB->Rollback();
				throw new Exception('Delete error');
			}
			$DB->Commit();
		} else {
			return false;
		}
	}

	/**
	 * Сохраняет группу
	 * @return bool
	 */
	public function save()
	{
		$values = $this->getValues();
		$arFields = array();
		foreach ($values as $key => $value) {
			$arFields[strtoupper($key)] = $value;
		}
		$group = new CGroup;
		//записываем поля пользователя
		if (!$this->isNew()) {
			$id = $this->getAttribute('id')->getValue();
			//при обновлении элемента сначала записываем базовые поля
			$res = $group->Update($id, $arFields);
			if (!$res) {
				throw new Exception($group->LAST_ERROR);
			}
			$this->riseEvent('afterSave');
		} else {
			$new = $group->Add($arFields);
			if ($new) {
				$this->getAttribute('id')->setValue($new);
				$this->riseEvent('afterSave');
			} else {
				throw new Exception($group->LAST_ERROR);
			}
		}
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
		){
			$settings['type'] = 'numeric';
		} elseif (
			$settings['code'] == 'timestamp_x'
		){
			$settings['type'] = 'date';
		} elseif ($settings['code'] == 'active') {
			$settings['type'] = 'bitrixBool';
		}
		return Factory::create($settings);
	}
}