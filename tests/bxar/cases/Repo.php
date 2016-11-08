<?php

namespace marvin255\bxar\tests\cases;

abstract class Repo extends \PHPUnit_Framework_TestCase
{
    abstract public function getObject();

    public function testSetQuery()
    {
        $repo = $this->getObject();
        $query = $this->getMockBuilder('\marvin255\bxar\IQuery')->getMock();

        $this->assertSame(
            $repo,
            $repo->setQuery($query)
        );

        $this->assertSame(
            $query,
            $repo->getQuery()
        );

        $repo->setQuery();
        $this->assertSame(
            null,
            $repo->getQuery()
        );
    }

    public function testNewQuery()
    {
        $repo = $this->getObject();
        $query = $this->getMockBuilder('\marvin255\bxar\IQuery')->getMock();
        $query->expects($this->once())->method('clear');
        $query->expects($this->once())
            ->method('setRepo')
            ->with($this->equalTo($repo));
        $repo->setQuery($query);

        $this->assertSame(
            $query,
            $repo->newQuery()
        );
    }

    public function testNewQueryNoQueryObjectSet()
    {
        $repo = $this->getObject();
        $this->setExpectedException('\InvalidArgumentException');
        $repo->newQuery();
    }

    public function testSetModelClass()
    {
        $repo = $this->getObject();
        $model = $this->getMockBuilder('\marvin255\bxar\IModel')->getMock();
        $class = get_class($model);

        $this->assertSame(
            $repo,
            $repo->setModelClass($class)
        );

        $this->assertSame(
            $class,
            $repo->getModelClass()
        );
    }

    public function testSetModelClassWithBadClassName()
    {
        $repo = $this->getObject();
        $model = $this->getMockBuilder('\marvin255\bxar\IQuery')->getMock();
        $class = get_class($model);
        $this->setExpectedException('\InvalidArgumentException');
        $repo->setModelClass($class);
    }

    public function testEscapeFieldName()
    {
        $repo = $this->getObject();

        $this->assertSame(
            'test',
            $repo->escapeFieldName('    test ')
        );

        $this->assertSame(
            'new_test',
            $repo->escapeFieldName(' NEw_TeSt ')
        );
    }
}
