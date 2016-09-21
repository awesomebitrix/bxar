<?php

namespace bxar\attributes;

/**
 * Класс для множественного свойства
 */
class Multiple extends Attribute
{
	/**
	 * Возвращает значение для поля
	 * @return array
	 */
	public function getValue()
	{
		$return = array();
		$values = parent::getValue();
		$var = $this->getParams();
		foreach ($values as $key => $element) {
			$return[$key] = $element->getValue();
		}
		return $return;
	}

	/**
	 * Возвращает массив с объектами
	 * @return array
	 */
	public function getObjects()
	{
		return parent::getValue();
	}

	/**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		if ($value === null || $value === array()) return parent::setValue(array());
		$values = parent::getValue();
		$values = is_array($values) ? $values : array();
		$value = is_array($value) ? array_values($value) : array($value);
		foreach ($values as $key => $element) {
			if (array_key_exists($key, $value)) {
				$element->setValue($value[$key]);
			} else {
				unset($values[$key]);
			}
		}
		foreach ($value as $key => $val) {
			if (!isset($values[$key])) {
				$values[$key] = $this->initValueItem($key);
				$values[$key]->setValue($val);
			}
		}
		return parent::setValue($values);
	}

	/**
	 * Возвращает значение для записи в базу данных
	 * @return mixed
	 */
	public function getValueToDb()
	{
		$return = array();
		$value = parent::getValue();
		if (is_array($value)) {
			foreach ($value as $key => $element) {
				$return[$key] = $element->getValueToDb();
			}
		}
		return $return;
	}

	/**
	 * Инициирует новый элемент для значения множественного поля
	 * @param string $code
	 * @return \bxar\attributes\IblockProperty
	 */
	protected function initValueItem($code)
	{
		$model = $this->getModel();
		$params = $this->getParams();
		$params['MULTIPLE'] = 'N';
		$params['FROM_MULTIPLE'] = 'Y';
		$init = array(
			'model' => $model,
			'code' => $code,
			'params' => $params,
		);
		return $model->createAttributeFromSettings($init);
	}

	/**
	 * Возвращает читаемое значение для значения
	 * @return string
	 */
	public function getReadable()
	{
		$return = null;
		$objects = parent::getValue();
		foreach ($objects as $key => $value) {
			$return[$key] = $value->getReadable();
		}
		return $return;
	}
}
