<?php

namespace marvin255\bxar\repo;

use InvalidArgumentException;

/**
 * Базовый класс для хранилища данных.
 *
 * @see \marvin255\bxar\repo\RepoInterface
 */
class Repo implements RepoInterface
{
    /**
     * @var \marvin255\bxar\repo\ProviderInterface
     */
    protected $provider = null;
    /**
     * @var string
     */
    protected $modelName = null;

    /**
     * @param \marvin255\bxar\repo\ProviderInterface $provider
     * @param string                                 $modelName
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(\marvin255\bxar\repo\ProviderInterface $provider, $modelName)
    {
        $this->provider = $provider;
        if (empty($modelName) || !is_subclass_of($modelName, '\marvin255\bxar\model\ModelInterface')) {
            throw new InvalidArgumentException('Wrong model name: '.$modelName);
        } else {
            $this->modelName = $modelName;
        }
    }

    /**
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @return \marvin255\bxar\repo\ProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @var array
     */
    protected $fieldsDescription = null;

    /**
     * @return array
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function getFieldsDescription()
    {
        /*
        кэшируем в памяти описания полей, чтобы не запрашивать дважды
        кэшируем именно в хранилище, чтобы в провайдерах просто использовать
        метод для получения описаний
        */
        if ($this->fieldsDescription === null) {
            try {
                $res = $this->getProvider()->getFieldsDescription();
            } catch (\Exception $e) {
                /*
                ловим все исключения в классе хранилища и
                приводим их к единообразному виду
                */
                throw new Exception('Error while getting fields description: '.$e->getMessage());
            }
            if (is_array($res)) {
                $this->fieldsDescription = [];
                foreach ($res as $key => $value) {
                    //приводим имена полей к общему виду
                    $key = $this->encode($key);
                    $this->fieldsDescription[$key] = $value;
                }
            } else {
                throw new Exception('Field descriptions returned by provider must be an array instance');
            }
        }

        return $this->fieldsDescription;
    }

    /**
     * @param string $fieldName
     *
     * @return \marvin255\bxar\model\FieldInterface
     *
     * @throws \InvalidArgumentException
     * @throws \marvin255\bxar\repo\Exception
     */
    public function createFieldHandler($fieldName)
    {
        $fieldsDescription = $this->getFieldsDescription();
        $encodedFieldName = $this->encode($fieldName);
        if (!isset($fieldsDescription[$encodedFieldName])) {
            throw new InvalidArgumentException('Field description not found: '.$fieldName);
        }
        try {
            $field = $this->getProvider()->createFieldHandler(
                $encodedFieldName,
                $fieldsDescription[$encodedFieldName],
                $this
            );
        } catch (\Exception $e) {
            /*
            ловим все исключения в классе хранилища и
            приводим их к единообразному виду
            */
            throw new Exception('Error while creating field handler: '.$e->getMessage());
        }
        if (!($field instanceof \marvin255\bxar\model\FieldInterface)) {
            throw new Exception('Error while creating field handler: provider returned wrong field object');
        }

        return $field;
    }

    /**
     * @param \marvin255\bxar\query\QueryInterface $query
     *
     * @return \marvin255\bxar\model\ModelInterface|null
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function one(\marvin255\bxar\query\QueryInterface $query)
    {
        $query->setLimit(1);
        $res = $this->all($query);

        return $res ? reset($res) : null;
    }

    /**
     * @param \marvin255\bxar\query\QueryInterface $query
     *
     * @return array
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function all(\marvin255\bxar\query\QueryInterface $query)
    {
        try {
            $res = $this->getProvider()->search($query);
        } catch (\Exception $e) {
            /*
            ловим все исключения в классе хранилища и
            приводим их к единообразному виду
            */
            throw new Exception('Error while searching: '.$e->getMessage());
        }
        if (!is_array($res)) {
            throw new Exception('Provider must return array from search');
        }
        $return = [];
        foreach ($res as $key => $data) {
            $return[$key] = $this->initModel($data);
        }

        return $return;
    }

    /**
     * @param \marvin255\bxar\query\QueryInterface $query
     *
     * @return int
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function count(\marvin255\bxar\query\QueryInterface $query)
    {
        try {
            $res = $this->getProvider()->count($query);
        } catch (\Exception $e) {
            /*
            ловим все исключения в классе хранилища и
            приводим их к единообразному виду
            */
            throw new Exception('Error while counting: '.$e->getMessage());
        }

        return (int) $res;
    }

    /**
     * @param \marvin255\bxar\model\ModelInterface $model
     *
     * @return bool
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function save(\marvin255\bxar\model\ModelInterface $model)
    {
        try {
            $res = $this->getProvider()->save($model);
        } catch (\Exception $e) {
            /*
            ловим все исключения в классе хранилища и
            приводим их к единообразному виду
            */
            throw new Exception('Error while saving: '.$e->getMessage());
        }

        return (bool) $res;
    }

    /**
     * @param \marvin255\bxar\model\ModelInterface $model
     *
     * @return bool
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function delete(\marvin255\bxar\model\ModelInterface $model)
    {
        try {
            $res = $this->getProvider()->delete($model);
        } catch (\Exception $e) {
            /*
            ловим все исключения в классе хранилища и
            приводим их к единообразному виду
            */
            throw new Exception('Error while deleting: '.$e->getMessage());
        }

        return (bool) $res;
    }

    /**
     * @param array $data
     *
     * @return \marvin255\bxar\model\ModelInterface
     */
    public function initModel(array $data = null)
    {
        $class = $this->getModelName();
        $model = new $class($this);
        if ($data !== null) {
            $model->setAttributesValues($data);
        }

        return $model;
    }

    /**
     * @param string $encode
     *
     * @return string
     */
    public function encode($encode)
    {
        return str_replace(' ', '_', strtolower(trim($encode)));
    }
}
