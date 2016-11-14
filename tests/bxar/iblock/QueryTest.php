<?php

namespace marvin255\bxar\tests\iblock;

use marvin255\bxar\tests\cases\Query;

class QueryTest extends Query
{
    public function testAndFilter()
    {
        $query = $this->getObject();
        $filter = ['ID' => 1];

        $this->assertSame(
            $query,
            $query->andFilter($filter)
        );

        $this->assertSame(
            $filter,
            $query->getFilter()
        );

        $this->assertSame(
            ['ID' => 1, 'NAME' => 'test'],
            $query->andFilter(['NAME' => 'test'])->getFilter()
        );

        $this->assertSame(
            ['ID' => 1, 'NAME' => 'test12', 'PROPERTY' => 'test21'],
            $query->andFilter(['NAME' => 'test12'])->andFilter(['PROPERTY' => 'test21'])->getFilter()
        );
    }

    public function testOrFilter()
    {
        $query = $this->getObject();
        $filter = ['ID' => 1];

        $this->assertSame(
            $query,
            $query->orFilter($filter)
        );

        $this->assertSame(
            $filter,
            $query->getFilter()
        );

        $this->assertSame(
            ['LOGIC' => 'OR', ['ID' => 1], ['NAME' => 'test']],
            $query->orFilter(['NAME' => 'test'])->getFilter()
        );

        $this->assertSame(
            ['LOGIC' => 'OR', ['ID' => 1], ['NAME' => 'test'], ['NAME' => 'test12']],
            $query->orFilter(['NAME' => 'test12'])->getFilter()
        );
    }

    public function testFilterCombinations()
    {
        $query = $this->getObject();

        $this->assertSame(
            [
                'LOGIC' => 'AND',
                [
                    'LOGIC' => 'OR',
                    ['NAME' => 'test'],
                    ['NAME' => 'test12'],
                ],
                ['ID' => 10],
            ],
            $query->orFilter(['NAME' => 'test'])
                ->orFilter(['NAME' => 'test12'])
                ->andFilter(['ID' => 10])
                ->getFilter()
        );

        $query->setFilter();
        $this->assertSame(
            [
                'LOGIC' => 'OR',
                [
                    'NAME' => 'test',
                    'PROPERTY' => 'test12',
                ],
                ['ID' => 10],
            ],
            $query->andFilter(['NAME' => 'test'])
                ->andFilter(['PROPERTY' => 'test12'])
                ->orFilter(['ID' => 10])
                ->getFilter()
        );
    }

    public function getObject()
    {
        return new \marvin255\bxar\iblock\Query();
    }
}
