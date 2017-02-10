<?php

namespace marvin255\bxar\model;

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
     * @var \marvin255\bxar\repo\RepoInterface
     */
    protected $repo = null;

    /**
     * @param \marvin255\bxar\repo\RepoInterface $repo
     */
    public function __construct(\marvin255\bxar\repo\RepoInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return \marvin255\bxar\repo\RepoInterface
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param string $name
     *
     * @return \marvin255\bxar\model\FieldInterface
     */
    public function getAttribute($name)
    {
        $name = $this->getRepo()->encode($name);
        if (!isset($this->attributes[$name])) {
            $field = $this->getRepo()->createFieldHandler($name);
            $field->setModel($this);
            $this->attributes[$name] = $field;
        }

        return $this->attributes[$name];
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
        $description = $this->getRepo()->getFieldsDescription();
        foreach ($description as $fieldName => $fieldDescription) {
            $return[$fieldName] = $this->getAttribute($fieldName)->getValue();
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getAttributesErrors()
    {
        $return = [];
        $description = $this->getRepo()->getFieldsDescription();
        foreach ($description as $fieldName => $fieldDescription) {
            $return[$fieldName] = $this->getAttribute($fieldName)->getErrors();
        }

        return $return;
    }

    /**
     * @return bool
     */
    public function save()
    {
        return $this->getRepo()->save($this);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        return $this->getRepo()->delete($this);
    }
}
