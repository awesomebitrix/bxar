<?php

namespace marvin255\bxar\iblock;

use marvin255\bxar\IRepo;
use marvin255\bxar\traits\Repo as TRepo;

/**
 * Хранилище данных в инфоблоке.
 */
class Repo implements IRepo
{
	use TRepo;
}