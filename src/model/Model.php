<?php

namespace marvin255\bxar\model;

use InvalidArgumentException;

/**
 * Базовый класс для модели.
 *
 * @see \marvin255\bxar\model\ModelInterface
 */
class Model implements ModelInterface
{
    /**
     * @param string $name
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $key => $attr) {
            if ($attr instanceof \marvin255\bxar\model\FieldInterface) {
                continue;
            }
            throw new InvalidArgumentException($key.' attribute object must be an \marvin255\bxar\model\FieldInterface instance');
        }
        $this->attributes = $attributes;
    }

    /**
     * @param string $name
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function getAttribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    /**
     * @param array $attributes
     *
     * @return \marvin255\bxar\model\ModelInterface
     */
    public function setAttributesValues(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->getAttribute($name)->setValue($value);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributesValues()
    {
        $return = [];
        foreach ($this->attributes as $fieldName => $field) {
            $return[$fieldName] = $field->getValue();
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getAttributesErrors()
    {
        $return = [];
        foreach ($this->attributes as $fieldName => $field) {
            $return[$fieldName] = $field->getErrors();
        }

        return $return;
    }
}
