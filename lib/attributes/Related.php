<?php

namespace bxar\attributes;

/**
 * Класс для свойства привязки к элементу
 */
class Related extends Attribute
{
	/**
	 * @var \bxar\ActiveRecord связанный элемент
	 */
	protected $_related = null;


	/**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$this->_related = null;
		parent::setValue($value);
	}

	/**
	 * Возвращает значение для записи в базу данных
	 * @return mixed
	 */
	public function getValueToDb()
	{
		return $this->getValue();
	}

	/**
	 * Возвращает модель связанного элемента
	 * @return \bxar\ActiveRecord
	 */
	public function getRelated()
	{
		$id = $this->getValue();
		if ($id !== null && $this->_related === null) {
			$params = $this->getParams();
			$this->_related = \bxar\element\Finder::find(array('IBLOCK_ID' => $params['LINK_IBLOCK_ID'], 'ID' => $id))->one();
		}
		return $this->_related;
	}

	/**
	 * Задает модель связанного элемента
	 * @param \bxar\ActiveRecord $model
	 */
	public function setRelated(\bxar\ActiveRecord $model)
	{
		$id = $model->getAttributeValue('ID');
		if ($id) {
			$this->setValue($id);
			$this->_related = $model;
		}
	}
}