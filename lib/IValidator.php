<?php

namespace bxar;

/**
 * Интерфейс для валидатора модели
 */
interface IValidator
{
	/**
	 * @param array $fields
	 * @param bool $setErrors
	 * @return bool
	 */
	public function validate(array $fields = null, $setErrors = true);

	/**
	 * @param $value
	 */
	public function setOn(array $value);

	/**
	 * @return array
	 */
	public function getOn();

	/**
	 * @param array $value
	 */
	public function setAttributes($value);

	/**
	 * @return array
	 */
	public function getAttributes();

	/**
	 * @param \bxar\IActiveRecord $model
	 */
	public function setModel(\bxar\IActiveRecord $value);

	/**
	 * @return \bxar\IActiveRecord|null
	 */
	public function getModel();
}