<?php

namespace bx\ar;

/**
 * Автозагрузчик для классов
 */
class Autoloader
{
	/**
	 * @param string путь до папки с проектом
	 */
	protected static $_path = null;
	/**
	 * Регистрирует автозагрузчик
	 * @param string $path
	 */
	public static function register($path = null)
	{
		self::$_path = $path ? $path : dirname(__FILE__);
		return spl_autoload_register(array(__CLASS__, 'load'), true, true);
	}
	/**
	 * Загружает файлы классов
	 */
	public static function load($class)
	{
		$prefix = __NAMESPACE__ . '\\';
		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) !== 0) {
			return;
		}
		$relative_class = substr($class, $len);
		$file = self::$_path . '/' . str_replace('\\', '/', $relative_class) . '.php';
		if (file_exists($file)) {
			require $file;
		}
	}
}

\bx\ar\Autoloader::register(dirname(__FILE__));