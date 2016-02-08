<?php

namespace bxar\user;


use \bxar\attributes\Factory;


/**
 * Active record для пользователей
 */
class User extends \bxar\ActiveRecord
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
			'TIMESTAMP_X',
			'LOGIN',
			'ACTIVE',
			'NAME',
			'LAST_NAME',
			'EMAIL',
			'LAST_LOGIN',
			'LID',
			'PERSONAL_PROFESSION',
			'PERSONAL_WWW',
			'PERSONAL_ICQ',
			'PERSONAL_GENDER',
			'PERSONAL_BIRTHDATE',
			'PERSONAL_PHOTO',
			'PERSONAL_PHONE',
			'PERSONAL_FAX',
			'PERSONAL_MOBILE',
			'PERSONAL_PAGER',
			'PERSONAL_STREET',
			'PERSONAL_MAILBOX',
			'PERSONAL_CITY',
			'PERSONAL_STATE',
			'PERSONAL_ZIP',
			'PERSONAL_COUNTRY',
			'PERSONAL_NOTES',
			'WORK_COMPANY',
			'WORK_DEPARTMENT',
			'WORK_WWW',
			'WORK_PHONE',
			'WORK_FAX',
			'WORK_PAGER',
			'WORK_STREET',
			'WORK_MAILBOX',
			'WORK_CITY',
			'WORK_STATE',
			'WORK_ZIP',
			'WORK_COUNTRY',
			'WORK_PROFILE',
			'WORK_LOGO',
			'WORK_NOTES',
			'ADMIN_NOTES',
			'XML_ID',
			'SECOND_NAME',
			'LAST_ACTIVITY_DATE',
			'TIME_ZONE',
			'TIME_ZONE_OFFSET',
			'LANGUAGE_ID',
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
		$userFields = $this->getUserFieldsDescription();
		foreach ($userFields as $field) {
			$name = $this->prepareAttributeName($field['FIELD_NAME']);
			$this->_initOnDemand[$name] = isset($init[$field['FIELD_NAME']]) ? $init[$field['FIELD_NAME']] : null;
		}
	}


	/**
	 * Возвращает массив для валидации полей модели
	 * @return array
	 */
	protected function rules()
	{
		if ($this->_rules !== null) return $this->_rules;

		$required = array('login', 'email');
		$default = array(
			array('active'),
			'default',
			'defaultArray' => array('active' => 'Y')
		);

		$descr = $this->getUserFieldsDescription();
		foreach ($descr as $field) {
			if (!empty($field['MANDATORY']) && $field['MANDATORY'] === 'Y') {
				$required[] = $this->prepareAttributeName($field['FIELD_NAME']);
			}
		}

		$this->_rules = array();
		if (!empty($default[0]) && $this->isNew()) $this->_rules[] = $default;
		if (!empty($required)) $this->_rules[] = array($required, 'required');
		return $this->_rules;
	}


	/**
	 * Удаляет пользователя
	 * @return bool
	 */
	public function delete()
	{
		$id = $this->getAttribute('id')->getValue();
		if ($id > 0) {
			$res = \CUser::Delete($id);
			if ($res) $this->getAttribute('id')->setValue(0);
			return $res;
		} else {
			return false;
		}
	}

	/**
	 * Сохраняет пользователя
	 * @return bool
	 */
	public function save()
	{
		if (!$this->validate() || !$this->riseEvent('beforeSave')) return false;
		$values = $this->getValues();
		$arFields = array();
		foreach ($values as $key => $value) {
			$arFields[strtoupper($key)] = $value;
		}
		if (!is_array($arFields['PERSONAL_PHOTO'])) {
			unset($arFields['PERSONAL_PHOTO']);
		}
		$user = new \CUser;
		//записываем поля пользователя
		if (!$this->isNew()) {
			$id = $this->getAttribute('id')->getValue();
			//при обновлении элемента сначала записываем базовые поля
			$res = $user->Update($id, $arFields);
			if (!$res) {
				throw new Exception($user->LAST_ERROR);
			}
			$this->riseEvent('afterSave');
		} else {
			$new = $user->Add($arFields);
			if ($new) {
				$this->getAttribute('id')->setValue($new);
				$this->riseEvent('afterSave');
			} else {
				throw new Exception($user->LAST_ERROR);
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
			$desc = $this->getUserFieldsDescription();
			foreach ($desc as $field) {
				$fName = $this->prepareAttributeName($field['FIELD_NAME']);
				if ($fName === $name) {
					$init['params'] = $field;
					break;
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
		if (
			$settings['code'] == 'id'
		){
			$settings['type'] = 'numeric';
		} elseif (
			$settings['code'] == 'timestamp_x'
			|| $settings['code'] == 'last_login'
			|| $settings['code'] == 'date_register'
			|| $settings['code'] == 'personal_birthdate'
		){
			$settings['type'] = 'date';
		} elseif ($settings['code'] == 'active') {
			$settings['type'] = 'bitrixBool';
		}
		return Factory::create($settings);
	}


	/**
	 * Возвращает описание пользовательских полей
	 * @return array
	 */
	protected function getUserFieldsDescription()
	{
		return \bxar\helpers\Uf::getListFor('USER');
	}
}