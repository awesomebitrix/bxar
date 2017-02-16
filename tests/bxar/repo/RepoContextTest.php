<?php

namespace marvin255\bxar\tests\bxar\repo;

class RepoContextTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorWithWrongQueryClass()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $this->setExpectedException(
            'InvalidArgumentException',
            'Wrong query class: 123'
        );
        $context = new \marvin255\bxar\repo\RepoContext($provider, 123);
    }

    public function testCreateQuery()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $query = get_class(
            $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')->getMock()
        );
        $context = new \marvin255\bxar\repo\RepoContext(
            $provider,
            $query
        );
        $this->assertInstanceOf(
            $query,
            $context->createQuery(),
            'Find method must return an instance of class that was set in constructor'
        );
    }

    public function testCallMagic()
    {
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')->getMock();
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')
            ->getMock();
        $repo->expects($this->once())
            ->method('all')
            ->with($this->equalTo($query))
            ->will($this->returnValue(123));
        $context = new \marvin255\bxar\repo\RepoContext(
            null,
            get_class($query),
            null,
            $repo
        );
        $this->assertSame(
            123,
            $context->all($query),
            'Context must call provider\'s methods if it does not have self method with same name'
        );
    }
}
