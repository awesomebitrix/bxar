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
		'numeric' => '\bx\ar\attributes\Numeric',
		'date' => '\bx\ar\attributes\Date',
		'bitrixBool' => '\bx\ar\attributes\BitrixBool',
		'list' => '\bx\ar\attributes\ListProperty',
		'file' => '\bx\ar\attributes\File',
		'related' => '\bx\ar\attributes\Related',
		'multiple' => '\bx\ar\attributes\Multiple',
		'bitrixSection' => '\bx\ar\attributes\BitrixSection',
	);


	/**
	 * Создает и инициирует атрибут
	 * @param array $config
	 * @return \bx\ar\IAttribute
	 */
	public static function create(array $config)
	{
		$class = !isset($config['type']) ? self::$_classMap['default'] : (!empty(self::$_classMap[$config['type']]) ? self::$_classMap[$config['type']] : null);
		if (!is_subclass_of($class, '\bx\ar\IAttribute')) {
			throw new Exception("Type class {$class} must implements \bx\ar\IAttribute");
		} else {
			$attribute = new $class;
			$attribute->initAttributes($config);
			return $attribute;
		}
	}
}