<?php

namespace bxar;

use UnexpectedValueException;
use InvalidArgumentException;

/**
 * Трэйт, который реализует базовые функции IFieldManager
 */
interface TFieldManager
{
    /**
     * Обрабатывает название поля для того,
     * чтобы привести все названия к единообразю.
     *
     * @param string $name
     *
     * @return string
     */
    public function escapeFieldName($name)
    {
        return strtolower(trim($name));
    }

    /**
     * @var array
     */
    protected $_readyFields = [];

    /**
     * Возвращает объект, который представляет собой
     * обработчик для конкретного поля.
     *
     * @param string $name
     *
     * @return \bxar\IField
     *
     * @throws \InvalidArgumentException
     */
    public function getField($name)
    {
        $field = null;
        $name = $this->escapeFieldName($name);
        if (isset($this->_readyFields[$name])) {
            $field = $this->_readyFields[$name];
            $field->clearField();
        } else {
            $descriptions = $this->getFieldsDescription();
            if (isset($descriptions[$name])) {
                $field = $this->createField($descriptions[$name]);
                if ($field) {
                    $this->_readyFields[$name] = $field;
                }
            }
        }
        if ($field === null) {
            throw new InvalidArgumentException("Can not create field: {$name}");
        }
        return $field;
    }
}
