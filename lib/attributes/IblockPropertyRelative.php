<?php

namespace bx\ar\attributes;

/**
 * Привязка к элементам инфоблока
 */
class IblockPropertyRelative extends IblockProperty
{
	/**
	 * @var \bx\ar\ActiveRecord связанный элемент
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
	 * @return \bx\ar\ActiveRecord
	 */
	public function getRelated()
	{
		$id = $this->getValue();
		if ($id !== null && $this->_related === null) {
			$params = $this->getParams();
			$this->_related = \bx\ar\element\Element::find(array('IBLOCK_ID' => $params['LINK_IBLOCK_ID'], 'ID' => $id))->one();
		}
		return $this->_related;
	}

	/**
	 * Задает модель связанного элемента
	 * @param \bx\ar\ActiveRecord $model
	 */
	public function setRelated(\bx\ar\ActiveRecord $model)
	{
		$id = $model->getAttributeValue('ID');
		if ($id) {
			$this->setValue($id);
			$this->_related = $model;
		}
	}
}