<?php

namespace marvin255\bxar\tests\bxar\iblock;

use marvin255\bxar\tests\cases\QueryBitrixLogic;

class QueryTest extends QueryBitrixLogic
{
    public function getObject()
    {
        return new \marvin255\bxar\iblock\Query();
    }
}
