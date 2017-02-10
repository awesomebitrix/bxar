<?php

namespace marvin255\bxar\model;

/**
 * Базовый класс для атрибута модели.
 *
 * @see \marvin255\bxar\model\FieldInterface
 */
class Field implements FieldInterface
{
    /**
     * @var string
     */
    protected $name = null;

    /**
     * @param mixed $name
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @var \marvin255\bxar\model\ModelInterface
     */
    protected $model = null;

    /**
     * @param \marvin255\bxar\model\ModelInterface $model
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function setModel(\marvin255\bxar\model\ModelInterface $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return \marvin255\bxar\model\ModelInterface
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        $params = $this->getModel()->getRepo()->getFieldsDescription();
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
