<?php

namespace bx\ar\attributes;

/**
 * Фабрика для создания атрибутов
 */
class Factory
{
	/**
	 * @var array соответствия между заданным классом атрибута и создаваемым объектом
	 */
	protected static $_classMap = array(
		'default' => '\bx\ar\attributes\Attribute',
	);


	/**
	 * Создает и инициирует атрибут
	 * @param string $code
	 * @param string $type
	 * @param array $options
	 * @throw \bx\ar\attributes\Exception
	 * @return \bx\ar\IAttribute
	 */
	public static function create($code, $type = null, array $options = null)
	{
		$class = $type == null ? self::$_classMap['default'] : (!empty(self::$_classMap[$type]) ? self::$_classMap[$type] : null);
		if (!is_subclass_of($class, '\bx\ar\IAttribute')) {
			throw new Exception('Type class must implements \bx\ar\IAttribute');
		} else {
			$attribute = new $class;
			$attribute->setCode($code);
			$attribute->setParams($options);
			return $attribute;
		}
	}

	/**
	 * Создает и нициирует атрибуты из массива
	 * @param array $attributes
	 * @return array
	 */
	public static function createFromArray(array $attributes)
	{
		$return = array();
		foreach ($attributes as $code => $options) {
			$type = null;
			if (!empty($options['type'])) {
				$type = $options['type'];
				unset($options['type']);
			}
			$return[$code] = self::create($code, $type, $options);
		}
		return $return;
	}
}