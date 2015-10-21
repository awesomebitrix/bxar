<?php

namespace bxar\attributes;

/**
 * Фабрика для создания атрибутов
 */
class Factory
{
	/**
	 * @var array соответствия между заданным классом атрибута и создаваемым объектом
	 */
	protected static $_classMap = array(
		'default' => '\bxar\attributes\Attribute',
		'numeric' => '\bxar\attributes\Numeric',
		'date' => '\bxar\attributes\Date',
		'bitrixBool' => '\bxar\attributes\BitrixBool',
		'list' => '\bxar\attributes\ListProperty',
		'file' => '\bxar\attributes\File',
		'related' => '\bxar\attributes\Related',
		'multiple' => '\bxar\attributes\Multiple',
		'bitrixSection' => '\bxar\attributes\BitrixSection',
	);


	/**
	 * Создает и инициирует атрибут
	 * @param array $config
	 * @return \bxar\IAttribute
	 */
	public static function create(array $config)
	{
		$class = !isset($config['type']) ? self::$_classMap['default'] : (!empty(self::$_classMap[$config['type']]) ? self::$_classMap[$config['type']] : null);
		if (!is_subclass_of($class, '\bxar\IAttribute')) {
			throw new Exception("Type class {$class} must implements \bxar\IAttribute");
		} else {
			$attribute = new $class;
			$attribute->initAttributes($config);
			return $attribute;
		}
	}
}