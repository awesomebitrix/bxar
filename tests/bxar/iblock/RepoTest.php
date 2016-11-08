<?php

namespace marvin255\bxar\tests\iblock;

use marvin255\bxar\tests\cases\Repo;

class RepoTest extends Repo
{
    public function getObject()
    {
        return new \marvin255\bxar\iblock\Repo();
    }
}
