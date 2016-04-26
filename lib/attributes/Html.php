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
}
