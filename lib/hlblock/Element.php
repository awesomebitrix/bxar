<?php

namespace bxar\hlblock;


use \bxar\attributes\Factory;


/**
 * Active record для элементов highload инфоблоков
 */
class Element extends \bxar\ActiveRecord
{
	/**
	 * @var string
	 */
	protected $_entity = null;
	/**
	 * @var array
	 */
	protected $_rules = null;


	/**
	 * Создает атрибуты Active Record
	 * @param array $init
	 */
	public function initAttributes(array $init)
	{
		$entity = $this->getEntity();
		//без указания сущности мы не сможем составить модель
		if (empty($entity)) {
			throw new Exception('entity must be valid');
		}
		//идентификатор есть у каждой таблицы
		$name = $this->prepareAttributeName('ID');
		$obj = $this->createAttributeFromSettings([
			'model' => $this,
			'code' => $name,
			'value' => isset($init['ID']) ? $init['ID'] : null,
		]);
		$this->setAttribute($name, $obj);
		//для остальных собираем данные из свойств
		$properties = \bxar\helpers\HlEntity::getFields($entity);		
		foreach ($properties as $property) {
			$code = !empty($property['FIELD_NAME']) ? $property['FIELD_NAME'] : $property['ID'];
			$preparedCode = $this->prepareAttributeName($code);
			$obj = $this->createAttributeFromSettings([
				'model' => $this,
				'code' => $preparedCode,
				'value' => isset($init[$property['FIELD_NAME']]) ? $init[$property['FIELD_NAME']] : null,
				'params' => $property,
			]);
			$this->setAttribute($preparedCode, $obj);
		}
	}


	/**
	 * Возвращает массив для валидации полей модели
	 * @return array
	 */
	protected function rules()
	{
		if ($this->_rules !== null) return $this->_rules;
		$this->_rules = array();
		return $this->_rules;
	}


	/**
	 * Удаляет запись
	 * @return bool
	 */
	public function delete()
	{
		$id = $this->getAttribute('id')->getValue();
		if ($id > 0) {
			$class = \bxar\helpers\HlEntity::compile($this->getEntity());
			$res = $class::delete($id);
			return $res->isSuccess();
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
		if (!$this->validate() || !$this->riseEvent('beforeSave')) return false;

		$class = \bxar\helpers\HlEntity::compile($this->getEntity());
		$arLoad = array();
		foreach ($this->getAttributes() as $key => $attr) {
			$arLoad[strtoupper($key)] = $attr->getValueToDb();
		}

		if (!$this->isNew()) {
			$id = $this->getAttribute('id')->getValue();
			$res = $class::update($id, $arLoad);
			if (!$res->isSuccess()) {
				$errors = implode(', ', $res->getErrorMessages());
				throw new Exception($errors);
			}
			$this->riseEvent('afterSave');
		} else {
			$res = $class::add($arLoad);
			if (!$res->isSuccess()) {
				$errors = implode(', ', $res->getErrorMessages());
				throw new Exception($errors);
			}
			$this->getAttribute('id')->setValue($res->getId());
			$this->riseEvent('afterSave');
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
	 * Создает атрибут из массива с настройками
	 * @param array $settings
	 * @return \bxar\IAttribute
	 */
	public function createAttributeFromSettings(array $settings)
	{
		if ($settings['params']['USER_TYPE_ID'] == 'integer') {
			$settings['type'] = 'numeric';
		}
		return Factory::create($settings);
	}


	/**
	 * Задает сущность
	 * @param string $entity
	 * @return \bxar\hlblock\Element
	 */
	public function setEntity($entity)
	{
		$this->_entity = trim($entity);
		return $this;
	}

	/**
	 * Возвращает сущность
	 * @return string
	 */
	public function getEntity()
	{
		return $this->_entity;
	}
}