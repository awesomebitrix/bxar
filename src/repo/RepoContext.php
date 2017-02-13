<?php

namespace marvin255\bxar\repo;

use InvalidArgumentException;

/**
 * Базовый класс для контекста хранилища.
 *
 * @see \marvin255\bxar\repo\RepoContextInterface
 */
class RepoContext implements RepoContextInterface
{
    /**
     * @var string
     */
    protected $queryClass = null;
    /**
     * @var \marvin255\bxar\repo\RepoInterface
     */
    protected $repo = null;

    /**
     * @param \marvin255\bxar\repo\ProviderInterface $provider
     * @param string $query
     * @param string $model
     * @param \marvin255\bxar\repo\RepoInterface $repo
     */
    public function __construct(
        $provider,
        $queryClass = '\marvin255\bxar\query\Query',
        $modelClass = '\marvin255\bxar\model\Model',
        \marvin255\bxar\repo\RepoInterface $repo = null
    ){
        //задаем класс для запросов к хранилищу
        if (is_subclass_of($queryClass, '\marvin255\bxar\query\QueryInterface')) {
            $this->queryClass = $queryClass;
        } else {
            throw new InvalidArgumentException('Wrong query class: '.$queryClass);
        }
        //инициируем хранилище
        if ($repo === null) {
            $this->repo = new \marvin255\bxar\repo\Repo($provider, $modelClass);
        } else {
            $this->repo = $repo;
        }
    }

    /**
     * Магия, которая при попытке вызвать несуществующий метод, ссылается на хранилище
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->repo, $name], $arguments);
    }

    /**
     * @return \marvin255\bxar\query\QueryInterface
     */
    public function find()
    {
        $class = $this->queryClass;
        $query = new $class;
        $query->setRepo($this->repo);
        return $query;
    }
}
