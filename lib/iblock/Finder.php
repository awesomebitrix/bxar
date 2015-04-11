<?php

namespace bx\ar\iblock;

/**
 * Класс для поиска инфоблоков по запросу
 */
class Finder extends \bx\ar\Finder
{
	/**
	 * @var array возвращать ли количество элементов в информационном блоке
	 */
	protected $_incCnt = false;


	/**
	 * Находит один элемент
	 * @return \bx\ar\IActiveRecord
	 */
	public function one()
	{
		$res = $this->getList($this->getOrder(), $this->getFilter(), $this->getIncCnt());
		if (!empty($res)) {
			$arInit = reset($res);
			return $this->getAsArray() ? $arInit : $this->initItem($arInit);
		} else {
			return null;
		}
	}

	/**
	 * Находит несколько элементов
	 * @return array
	 */
	public function all()
	{
		$limit = $this->getLimit();
		$offset = $this->getOffset();
		$from = $limit * ($offset - 1);
		$to = $limit * $offset - 1;
		$return = array();
		$res = $this->getList($this->getOrder(), $this->getFilter(), $this->getIncCnt());
		if (!empty($res)) {
			$class = $this->getArClass();
			$i = -1;
			foreach ($res as $key => $arInit) {
				$i++;
				if ($to > 0 && ($i < $from || $i > $to)) continue;
				$return[$key] = $this->getAsArray() ? $arInit : $this->initItem($arInit);
			}
		}
		return $return;
	}

	/**
	 * Находит количество элементов по запросу
	 * @return int
	 */
	public function count()
	{
		$res = $this->getList($this->getOrder(), $this->getFilter(), false);
		return count($res);
	}

	/**
	 * Подключаем стандартный битриксовый апи для поиска
	 */
	protected function getList(array $order, array $filter, $incCnt = false)
	{
		$return = array();
		if (($cache = $this->getFromCache()) !== false) {
			$return = $cache;
		} elseif (\CModule::IncludeModule('iblock')) {
			$res = \CIBlock::GetList($order, $filter, $incCnt);
			while ($ob = $res->fetch()) {
				$rsSites = \CIBlock::GetSite($ob['ID']);
				while ($arSite = $rsSites->Fetch()) {
					$ob['SITE_ID'][] = $arSite['SITE_ID'];
				}
				$return[] = $ob;
			}
			$this->setToCache($return);
		}
		return $return;
	}


	/**
	 * Возвращать ли количество элементов для инфоблока
	 * @return \bx\ar\IFinder
	 * @param bool $count
	 */
	public function setIncCnt($count)
	{
		$this->_incCnt = (bool) $count;
		return $this;
	}

	/**
	 * Возвращать ли количество элементов для инфоблока
	 * @param bool $count
	 */
	public function getIncCnt()
	{
		return $this->_incCnt;
	}
}