<?php

namespace marvin255\bxar\repo;

/**
 * Класс контекста для использования хранилища. Настраивает провайдер, модель,
 * класс запроса. Позволяет работать с хранилищем, используя меньше кода.
 */
interface RepoContextInterface
{
    /**
     * В конструкторе нужно описать те классы, которые будем использовать для
     * данного контекста. Классы менять в процессе работы нельзя - следует создавать отдельный контекст.
     *
     * @param \marvin255\bxar\repo\ProviderInterface $provider
     * @param string                                 $query
     * @param string                                 $model
     * @param \marvin255\bxar\repo\RepoInterface     $repo
     */
    public function __construct(
        $provider,
        $queryClass = '\marvin255\bxar\query\Query',
        $modelClass = '\marvin255\bxar\model\Model',
        \marvin255\bxar\repo\RepoInterface $repo = null
    );

    /**
     * Возвращает объект запроса, привязанный к текущему хранилищу.
     *
     * @return \marvin255\bxar\query\QueryInterface
     */
    public function createQuery();
}
