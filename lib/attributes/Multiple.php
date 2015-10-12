<?php

namespace bx\ar\attributes;

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
		foreach ($values as $key => $element) {
			$return[$key] = $element->getValue();
		}
		return $return;
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
		$value = is_array($value) ? $value : array($value);
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
				$params = $element->getParams();
				$return[$key] = array(
					'VALUE' => $element->getValueToDb(),
					'DESCRIPTION' => is_array($params['DESCRIPTION']) && isset($params['DESCRIPTION'][$key]) ? $params['DESCRIPTION'][$key] : $params['DESCRIPTION'],
				);
			}
		}
		return $return;
	}

	/**
	 * Инициирует новый элемент для значения множественного поля
	 * @param string $code
	 * @return \bx\ar\attributes\IblockProperty
	 */
	protected function initValueItem($code)
	{
		$model = $this->getModel();	
		$init = array(
			'model' => $model,
			'code' => $code,
			'params' => $this->getParams(),
		);
		return $model->createAttributeFromSettings($init);
	}
}