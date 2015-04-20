<?php

namespace bx\ar\attributes;

/**
 * Файлы
 */
class IblockPropertyFile extends IblockProperty
{
	/**
	 * @var string путь к файлу из библиотеки битрикса
	 */
	protected $_path = null;


	/**
	 * Задает значение атрибута
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$this->_path = null;
		parent::setValue($value);
	}

	/**
	 * Возвращает путь к файлу, если он записан в библиотеку битрикса
	 * @return string
	 */
	public function getPath()
	{
		$val = $this->getValue();
		if ($val && $this->_path === null && is_numeric($val)) {
			$this->_path = \CFile::GetPath($val);
		}
		return $this->_path;
	}

	/**
	 * Возвращает массив с данными о картинке, пережатой до нужных размеров
	 * @return array
	 */
	public function getResized($width, $height, $type = null, $bInitSizes = false, array $arFilters = null, $bImmediate = false, $jpgQuality = false)
	{
		$return = null;
		$type = $type === null ? BX_RESIZE_IMAGE_PROPORTIONAL : $type;
		$path = $this->getPath();
		if ($path !== null) {
			$return = \CFile::ResizeImageGet(
				$path, 
				array('width' => $width, 'height' => $height),
				$type,
				$bInitSizes, 
				$arFilters, 
				$bImmediate, 
				$jpgQuality
			);
		}
		return !empty($return['src']) ? $return['src'] : null;
	}

	/**
	 * Возвращает значение для записи в базу данных
	 * @return mixed
	 */
	public function getValueToDb()
	{
		$return = null;
		$val = $this->getValue();
		if (is_numeric($val)) {
			$return = null;
		} elseif (file_exists($val)) {
			$return = \CFile::MakeFileArray($val);
			$return['del'] = 'Y';
			$return['module'] = 'iblock';
		} elseif (strpos($val, 'http://') === 0 && ($content = @file_get_contents($val)) !== false) {
			$extension = pathinfo($val, PATHINFO_EXTENSION);
			$temp = tempnam(sys_get_temp_dir(), 'TUX') . ($extension ? '.' . $extension : '');
			file_put_contents($temp, $content);
			$return = \CFile::MakeFileArray($temp);
			$return['del'] = 'Y';
			$return['module'] = 'iblock';
		}
		return $return;
	}
}