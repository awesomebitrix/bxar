<?php

namespace marvin255\bxar\tests\cases;

abstract class Repo extends \PHPUnit_Framework_TestCase
{
    abstract public function getObject();

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
        $class = get_class($this);
        $this->setExpectedException('\InvalidArgumentException');
        $repo->setModelClass($class);
    }
}