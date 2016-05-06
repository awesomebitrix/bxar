<?php

namespace bxar\attributes;

/**
 * Класс для свойства html/текст
 */
class Html extends Attribute
{
	/**
	 * Возвращает значение для записи в базу данных
	 * @return mixed
	 */
	public function getValueToDb()
	{
        $descr = trim($this->getParam('DESCRIPTION'));
		return ['VALUE' => $this->getValue(), 'DESCRIPTION' => $descr];
	}

    /**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value)
	{
        $val = [];
        if (isset($value['TEXT'])) {
            $val['TEXT'] = $value['TEXT'];
        } elseif (!is_array($value)) {
            $val['TEXT'] = $value;
        } else {
            $val['TEXT'] = '';
        }
        if (isset($value['TYPE'])) {
            $val['TYPE'] = $value['TYPE'];
        } else {
            $val['TYPE'] = 'text';
        }
		$this->_value = $val;
	}

    /**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function getValue()
	{
        return $this->_value;
	}
}
