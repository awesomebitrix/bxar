<?php

namespace marvin255\bxar\repo;

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
     * @var array
     */
    protected $fieldsDescription = null;
    /**
     * @var array
     */
    protected $fieldsPrototypes = null;

    /**
     * @param \marvin255\bxar\repo\ProviderInterface $provider
     * @param string                                 $modelName
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function __construct(\marvin255\bxar\repo\ProviderInterface $provider, $modelName = '\marvin255\bxar\model\Model')
    {
        $this->provider = $provider;
        if (is_subclass_of($modelName, '\marvin255\bxar\model\ModelInterface')) {
            $this->modelName = $modelName;
        } else {
            throw new Exception('Wrong model name: '.$modelName);
        }
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
            $res = $this->provider->search($query, $this->getFieldsDescription());
        } catch (\Exception $e) {
            throw new Exception('Error while searching: '.$e->getMessage());
        }

        return $this->initList($res);
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
            $res = (int) $this->provider->count($query, $this->getFieldsDescription());
        } catch (\Exception $e) {
            throw new Exception('Error while counting: '.$e->getMessage());
        }

        return $res;
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
            $res = $this->provider->validate($model, $this->getFieldsDescription());
            if ($res) {
                $res = (bool) $this->provider->save($model, $this->getFieldsDescription());
            }
        } catch (\Exception $e) {
            throw new Exception('Error while saving: '.$e->getMessage());
        }

        return $res;
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
            $res = (bool) $this->provider->delete($model, $this->getFieldsDescription());
        } catch (\Exception $e) {
            throw new Exception('Error while deleting: '.$e->getMessage());
        }

        return $res;
    }

    /**
     * @return array
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    public function getFieldsDescription()
    {
        if ($this->fieldsDescription === null) {
            try {
                $res = $this->provider->getFieldsDescription();
            } catch (\Exception $e) {
                throw new Exception('Error while getting fields\' descriptions: '.$e->getMessage());
            }
            if (empty($res) || !is_array($res)) {
                throw new Exception('Fields list must be an array instance');
            }
            foreach ($res as $key => $value) {
                $key = preg_replace('/[^0-9a-z_]/', '_', strtolower(trim($key)));
                $this->fieldsDescription[$key] = $value;
            }
        }

        return $this->fieldsDescription;
    }

    /**
     * @param array $attributes
     *
     * @return \marvin255\bxar\model\ModelInterface
     */
    public function init(array $attributes = null)
    {
        $fields = $this->createFields();
        $class = $this->modelName;
        $model = new $class($fields);
        if ($attributes) {
            $model->setAttributesValues($attributes);
        }

        return $model;
    }

    /**
     * Инициирует объекты для полей модели.
     * При первом обращении создает эталонный массив полей, которые нужно передать в модель.
     * Затем клонирует эталонные записи и возвращает клоны.
     *
     * @return array
     *
     * @throws \marvin255\bxar\repo\Exception
     */
    protected function createFields()
    {
        if ($this->fieldsPrototypes === null) {
            $fieldsDescription = $this->getFieldsDescription();
            foreach ($fieldsDescription as $key => $field) {
                $this->fieldsPrototypes[$key] = $this->provider->createFieldHandler(
                    $key,
                    $this,
                    $fieldsDescription
                );
                if (!($this->fieldsPrototypes[$key] instanceof \marvin255\bxar\model\FieldInterface)) {
                    throw new Exception('Field must be an instance of \marvin255\bxar\model\FieldInterface: '.$key);
                }
            }
        }
        $return = [];
        foreach ($this->fieldsPrototypes as $key => $proto) {
            $return[$key] = clone $proto;
        }

        return $return;
    }

    /**
     * Инициирует массив моделей из массива с атрибутами моделей.
     *
     * @param array $models
     *
     * @return array
     */
    protected function initList(array $models)
    {
        $return = [];
        foreach ($models as $key => $attributes) {
            $return[$key] = $this->init($attributes);
        }

        return $return;
    }
}
