<?php

namespace marvin255\bxar;

use InvalidArgumentException;

/**
 * Класс-диспетчер, в котором можно объявить несколько разных контекстов,
 * а затем получать контекст по его псевдониму.
 * Например, \marvin255\bxar\Dr::get('news')->find()->setFilter(['ID' => 10])->one();.
 */
class Dr
{
    /**
     * @var array
     */
    protected static $contexts = [];

    /**
     * Функция, с помощью которой можно задать псевдоним для контекста.
     *
     * @param string                                    $alias
     * @param \marvin255\bxar\repo\RepoContextInterface $context
     */
    public static function set($alias, \marvin255\bxar\repo\RepoContextInterface $context)
    {
        $alias = strtolower(trim($alias));
        self::$contexts[$alias] = $context;
    }

    /**
     * Задает контексты из массива.
     *
     * @param array $contexts
     */
    public static function setArray(array $contexts)
    {
        self::$contexts = [];
        foreach ($contexts as $alias => $context) {
            self::set($alias, $context);
        }
    }

    /**
     * Функция, которая возвращает контекст по его псевдониму.
     *
     * @param string $alias
     *
     * @return \marvin255\bxar\repo\RepoContextInterface
     *
     * @throws \InvalidArgumentException
     */
    public static function get($alias)
    {
        $alias = strtolower(trim($alias));
        if (!isset(self::$contexts[$alias])) {
            throw new InvalidArgumentException('Wrong alias name: '.$alias);
        }

        return self::$contexts[$alias];
    }
}
