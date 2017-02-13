<?php

namespace marvin255\bxar\tests\bxar\model;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    protected function getObject()
    {
        return new \marvin255\bxar\query\Query();
    }

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
            null,
            $query->setSelect()->getSelect()
        );
    }

    public function testSetOrder()
    {
        $query = $this->getObject();
        $order = ['ID' => 'asc', 'NAME' => 'DESC', 'EMPTY', 'WRONG_ORDER' => 'dessC'];
        $this->assertSame(
            $query,
            $query->setOrder($order)
        );
        $this->assertSame(
            $order,
            $query->getOrder()
        );
        $this->assertSame(
            null,
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
            null,
            $query->setFilter()->getFilter()
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
            null,
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
            null,
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
            null,
            $query->setIndex(null)->getIndex()
        );
    }

    public function testClear()
    {
        $query = $this->getObject();
        $query->setSelect(['ID'])
            ->setFilter(['NAME' => 'test'])
            ->setOrder(['NAME'])
            ->setLimit(10)
            ->setOffset(12)
            ->setIndex('test');
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
    }

    public function testOne()
    {
        $query = $this->getObject();
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')
            ->getMock();
        $repo->expects($this->once())
            ->method('one')
            ->with($this->equalTo($query))
            ->will($this->returnValue(123));
        $query->setRepo($repo);
        $this->assertSame(
            123,
            $query->one()
        );
    }

    public function testAll()
    {
        $query = $this->getObject();
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')
            ->getMock();
        $repo->expects($this->once())
            ->method('all')
            ->with($this->equalTo($query))
            ->will($this->returnValue(123));
        $query->setRepo($repo);
        $this->assertSame(
            123,
            $query->all()
        );
    }

    public function testCount()
    {
        $query = $this->getObject();
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')
            ->getMock();
        $repo->expects($this->once())
            ->method('count')
            ->with($this->equalTo($query))
            ->will($this->returnValue(123));
        $query->setRepo($repo);
        $this->assertSame(
            123,
            $query->count()
        );
    }
}
