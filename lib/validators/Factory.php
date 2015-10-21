<?php

namespace bxar\validators;

/**
 * Фабрика для создания валидаторов
 */
class Factory
{
	/**
	 * @var array соответствия между заданным классом атрибута и создаваемым объектом
	 */
	protected static $_classMap = array(
		'filter' => '\bxar\validators\Filter',
		'default' => '\bxar\validators\DefaultValue',
		'required' => '\bxar\validators\Required',
		'safe' => '\bxar\validators\Safe',
	);


	/**
	 * Создает и инициирует атрибут
	 * @param string $code
	 * @param string $type
	 * @param array $options
	 * @throw \bxar\attributes\Exception
	 * @return \bxar\IAttribute
	 */
	public static function create(array $rule)
	{
		$options = $rule;
		unset($options[0], $options[1]);
		$options['attributes'] = $rule[0];
		$type = $rule[1];
		$class = !empty(self::$_classMap[$type]) ? self::$_classMap[$type] : $type;
		if (!is_subclass_of($class, '\bxar\IValidator')) {
			throw new Exception('Type class ' . $class . ' must implements \bxar\IValidator');
		} else {
			$validator = new $class;
			foreach ($options as $name => $value) {
				$method = 'set' . ucfirst($name);
				if (property_exists($validator, $name)) {
					$validator->$name = $value;
				} elseif (method_exists($validator, $method)) {
					$validator->$method($value);
				}
			}
			return $validator;
		}
	}
}