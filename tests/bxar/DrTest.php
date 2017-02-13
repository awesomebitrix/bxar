<?php

namespace marvin255\bxar\tests\bxar;

class DrTest extends \PHPUnit_Framework_TestCase
{
    public function testSet()
    {
        $context = $this->getMockBuilder('\marvin255\bxar\repo\RepoContextInterface')
            ->getMock();
        \marvin255\bxar\Dr::set('   Test ', $context);
        $this->assertSame(
            $context,
            \marvin255\bxar\Dr::get('test'),
            'Dr must return same contexts that was set by set function'
        );
    }

    public function testGetWithWrongAlias()
    {
        $context = $this->getMockBuilder('\marvin255\bxar\repo\RepoContextInterface')
            ->getMock();
        \marvin255\bxar\Dr::setArray([]);
        $this->setExpectedException(
            'InvalidArgumentException',
            'Wrong alias name: test1'
        );
        \marvin255\bxar\Dr::get('test1');
    }

    public function testSetArray()
    {
        $context = $this->getMockBuilder('\marvin255\bxar\repo\RepoContextInterface')
            ->getMock();
        $context2 = $this->getMockBuilder('\marvin255\bxar\repo\RepoContextInterface')
            ->getMock();
        \marvin255\bxar\Dr::set('   Test ', $context);
        \marvin255\bxar\Dr::setArray([
            'test' => $context2,
            'test2' => $context,
        ]);
        $this->assertSame(
            $context2,
            \marvin255\bxar\Dr::get('test'),
            'setArray function must clear all contexts. Dr must return same contexts that was set by setArray function'
        );
    }
}
