<?php

namespace bxar\attributes;

/**
 * Класс для url ссылок dyenhb ,bnhbrcf
 */
class BitrixUrl extends Attribute
{
	/**
	 * Возвращает значение типа bool
	 * @return bool
	 */
	public function getAbsoluteUrl()
	{
		$path = $this->getValue();
		$domen = !empty(SITE_SERVER_NAME) ? SITE_SERVER_NAME : $_SERVER['HTTP_HOST'];
		return $path ? "http://{$domen}{$path}" : null;
	}
}