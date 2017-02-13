<?php

namespace marvin255\bxar\model;

use InvalidArgumentException;

/**
 * Базовый класс для атрибута модели.
 *
 * @see \marvin255\bxar\model\FieldInterface
 */
class Field implements FieldInterface
{
    /**
     * Магия для быстрого доступа к свойствам.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'value') {
            return $this->getValue();
        } elseif ($name === 'name') {
            return $this->getName();
        } elseif ($name === 'errors') {
            return $this->getErrors();
        } else {
            return $this->getParam($name);
        }
    }

    /**
     * Магия для быстрого доступа к свойствам.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        if ($name === 'value') {
            return $this->setValue($value);
        } else {
            return null;
        }
    }

    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var \marvin255\bxar\repo\RepoInterface
     */
    protected $repo = null;

    /**
     * @param string                             $name
     * @param \marvin255\bxar\repo\RepoInterface $repo
     */
    public function __construct($name, \marvin255\bxar\repo\RepoInterface $repo)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Name can not be empty');
        }
        $this->name = $name;
        $this->repo = $repo;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \marvin255\bxar\model\ModelInterface
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @var mixed
     */
    protected $value = null;

    /**
     * @param mixed $value
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        $params = $this->getRepo()->getFieldsDescription();
        $name = $this->getName();

        return isset($params[$name]) ? $params[$name] : [];
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getParam($name)
    {
        $params = $this->getParams();

        return isset($params[$name]) ? $params[$name] : null;
    }

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param string $error
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function addError($error)
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function clearErrors()
    {
        $this->errors = [];

        return $this;
    }
}
