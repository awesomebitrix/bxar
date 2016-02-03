<?php

namespace bxar\attributes;

/**
 * Класс для свойства с файлом
 */
class File extends Attribute
{
	/**
	 * @var string путь к файлу из библиотеки битрикса
	 */
	protected $_fileArray = null;


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
	 * Возвращает массив с описанием файла
	 * @return string
	 */
	public function getFileArray()
	{
		$val = $this->getValue();
		if ($val && $this->_fileArray === null && is_numeric($val)) {
			$this->_fileArray = \CFile::GetFileArray($val);
		}
		return $this->_fileArray;
	}

	/**
	 * Возвращает путь к файлу, если он записан в библиотеку битрикса
	 * @return string
	 */
	public function getPath()
	{
		$fileArray = $this->getFileArray();
		return !empty($fileArray['SRC']) ? $fileArray['SRC'] : null;
	}

	/**
	 * Возвращает путь до картинки вместе с доменом
	 * @return string
	 */
	public function getAbsoluteUrl()
	{
		$path = $this->getPath();
		$domen = !empty(SITE_SERVER_NAME) ? SITE_SERVER_NAME : $_SERVER['HTTP_HOST'];
		return $path ? "http://{$domen}{$path}" : null;
	}

	/**
	 * Возвращает массив с данными о картинке, пережатой до нужных размеров
	 * @return array
	 */
	public function getResized($width, $height, $type = null, $bInitSizes = false, array $arFilters = null, $bImmediate = false, $jpgQuality = false)
	{
		$return = null;
		$type = $type === null ? BX_RESIZE_IMAGE_PROPORTIONAL : $type;
		$fileArray = $this->getFileArray();
		if ($fileArray) {
			$return = \CFile::ResizeImageGet(
				$fileArray, 
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
		} elseif (is_array($val) && isset($val['tmp_name'])) {
			$ext = pathinfo($val['tmp_name'], PATHINFO_EXTENSION);
			if ($ext === '' && !empty($val['name'])) {
				$oldName = file_exists($val['tmp_name']) ? $val['tmp_name'] : $_SERVER['DOCUMENT_ROOT'] . $val['tmp_name'];
				$newName = dirname($oldName) . '/' . $val['name'];
				rename($oldName, $newName);
				$val['tmp_name'] = $newName;
				unlink($oldName);
			}
			if ($this->getParam('ID') !== null) {
				$val = ['VALUE' => $val];
			} else {
				$val['del'] = 'Y';				
			}
			$return = $val;
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
		} else {
			$return = array(
				'del' => 'Y',
			);
		}
		return $return;
	}
}