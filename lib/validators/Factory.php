<?php

namespace bx\ar\validators;

/**
 * Фабрика для создания валидаторов
 */
class Factory
{
	/**
	 * @var array соответствия между заданным классом атрибута и создаваемым объектом
	 */
	protected static $_classMap = array(
		'filter' => '\bx\ar\validators\Filter',
		'default' => '\bx\ar\validators\DefaultValue',
		'date' => '\bx\ar\validators\Date',
		'bxBool' => '\bx\ar\validators\BxBool',
		'required' => '\bx\ar\validators\Required',
	);


	/**
	 * Создает и инициирует атрибут
	 * @param string $code
	 * @param string $type
	 * @param array $options
	 * @throw \bx\ar\attributes\Exception
	 * @return \bx\ar\IAttribute
	 */
	public static function create(array $rule)
	{
		$options = $rule;
		unset($options[0], $options[1]);
		$options['attributes'] = $rule[0];
		$type = $rule[1];
		$class = !empty(self::$_classMap[$type]) ? self::$_classMap[$type] : $type;
		if (!is_subclass_of($class, '\bx\ar\IValidator')) {
			throw new Exception('Type class ' . $class . ' must implements \bx\ar\IValidator');
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