<?php

namespace marvin255\bxar\tests\cases;

abstract class Query extends \PHPUnit_Framework_TestCase
{
    abstract public function getObject();

    public function testSetSelect()
    {
        $query = $this->getObject();
        $select = ['ID', 'NAME', 'PROPERTY_TEST'];

        $this->assertSame(
            $query,
            $query->setSelect($select)
        );

        $this->assertSame(
            $select,
            $query->getSelect()
        );

        $this->assertSame(
            [],
            $query->setSelect()->getSelect()
        );
    }

    public function testSetOrder()
    {
        $query = $this->getObject();
        $order = ['ID' => 'asc', 'NAME' => 'DESC', 'EMPTY'];

        $this->assertSame(
            $query,
            $query->setOrder($order)
        );

        $this->assertSame(
            ['ID' => 'asc', 'NAME' => 'desc', 'EMPTY' => 'asc'],
            $query->getOrder()
        );

        $this->assertSame(
            [],
            $query->setOrder()->getOrder()
        );
    }

    public function testSetFilter()
    {
        $query = $this->getObject();
        $filter = ['ID' => 1, 'NAME' => 'test', 'EMPTY'];

        $this->assertSame(
            $query,
            $query->setFilter($filter)
        );

        $this->assertSame(
            $filter,
            $query->getFilter()
        );

        $this->assertSame(
            [],
            $query->setFilter()->getFilter()
        );
    }

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
            ['ID' => 1, 'NAME' => 'test'],
            $query->orFilter(['NAME' => 'test'])->getFilter()
        );
    }

    public function testSetLimit()
    {
        $query = $this->getObject();

        $this->assertSame(
            $query,
            $query->setLimit(10)
        );

        $this->assertSame(
            10,
            $query->getLimit()
        );

        $this->assertSame(
            5,
            $query->setLimit('5')->getLimit()
        );

        $this->assertSame(
            0,
            $query->setLimit(null)->getLimit()
        );
    }

    public function testSetOffset()
    {
        $query = $this->getObject();

        $this->assertSame(
            $query,
            $query->setOffset(10)
        );

        $this->assertSame(
            10,
            $query->getOffset()
        );

        $this->assertSame(
            5,
            $query->setOffset('5')->getOffset()
        );

        $this->assertSame(
            0,
            $query->setOffset(null)->getOffset()
        );
    }

    public function testSetIndex()
    {
        $query = $this->getObject();

        $this->assertSame(
            $query,
            $query->setIndex('test')
        );

        $this->assertSame(
            'test',
            $query->getIndex()
        );

        $this->assertSame(
            'test',
            $query->setIndex('  test  ')->getIndex()
        );

        $this->assertSame(
            '',
            $query->setIndex(null)->getIndex()
        );
    }

    public function testSetRepo()
    {
        $query = $this->getObject();
        $repo = $this->getMockBuilder('\marvin255\bxar\IRepo')->getMock();

        $this->assertSame(
            $query,
            $query->setRepo($repo)
        );

        $this->assertSame(
            $repo,
            $query->getRepo()
        );
    }

    public function testSearch()
    {
        $query = $this->getObject();
        $repo = $this->getMockBuilder('\marvin255\bxar\IRepo')->getMock();
        $repo->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query));
        $query->setRepo($repo)->search();
    }

    public function testSearchAll()
    {
        $query = $this->getObject();
        $repo = $this->getMockBuilder('\marvin255\bxar\IRepo')->getMock();
        $repo->expects($this->once())
            ->method('searchAll')
            ->with($this->equalTo($query));
        $query->setRepo($repo)->searchAll();
    }

    public function testCount()
    {
        $query = $this->getObject();
        $repo = $this->getMockBuilder('\marvin255\bxar\IRepo')->getMock();
        $repo->expects($this->once())
            ->method('count')
            ->with($this->equalTo($query));
        $query->setRepo($repo)->count();
    }

    public function testClear()
    {
        $query = $this->getObject();
        $repo = $this->getMockBuilder('\marvin255\bxar\IRepo')->getMock();
        $query->setSelect(['ID'])
            ->setFilter(['NAME' => 'test'])
            ->setOrder(['NAME'])
            ->setLimit(10)
            ->setOffset(12)
            ->setIndex('test')
            ->setRepo($repo);

        $this->assertSame(
            $query,
            $query->clear()
        );

        $this->assertSame(
            null,
            $query->getSelect()
        );

        $this->assertSame(
            null,
            $query->getFilter()
        );

        $this->assertSame(
            null,
            $query->getOrder()
        );

        $this->assertSame(
            null,
            $query->getLimit()
        );

        $this->assertSame(
            null,
            $query->getOffset()
        );

        $this->assertSame(
            null,
            $query->getIndex()
        );

        $this->assertSame(
            null,
            $query->getRepo()
        );
    }
}
