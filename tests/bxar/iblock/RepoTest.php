<?php

namespace marvin255\bxar\tests\iblock;

use marvin255\bxar\tests\cases\Repo;

class RepoTest extends Repo
{
    public function testSetIblockHelper()
    {
        $repo = $this->getObject();
        $iblockHelper = $this->getMockBuilder('\marvin255\bxar\iblock\IIblockHelper')->getMock();

        $this->assertSame(
            $repo,
            $repo->setIblockHelper($iblockHelper)
        );

        $this->assertSame(
            $iblockHelper,
            $repo->getIblockHelper()
        );
    }

    public function testGetIblockHelper()
    {
        $repo = $this->getObject();

        $this->assertInstanceOf(
            '\\marvin255\\bxar\\iblock\\IIblockHelper',
            $repo->getIblockHelper()
        );
    }

    public function testSetIblockWithInt()
    {
        $repo = $this->getObject();

        $iblockHelper = $this->getMockBuilder('\marvin255\bxar\iblock\IIblockHelper')->getMock();
        $iblockHelper->expects($this->never())->method('findIblockIdByCode');
        $repo->setIblockHelper($iblockHelper);

        $this->assertSame(
            $repo,
            $repo->setIblock(10)
        );

        $this->assertSame(
            10,
            $repo->getIblock()
        );

        $this->assertSame(
            5,
            $repo->setIblock('5')->getIblock()
        );
    }

    public function testSetIblockWithString()
    {
        $repo = $this->getObject();
        $iblockHelper = $this->getMockBuilder('\marvin255\bxar\iblock\IIblockHelper')->getMock();
        $iblockHelper->expects($this->once())
            ->method('findIblockIdByCode')
            ->with($this->equalTo('test'))
            ->will($this->returnValue(123));
        $repo->setIblockHelper($iblockHelper);
        $repo->setIblock('     test ');
        $repo->getIblock();
        $repo->getIblock();

        $this->assertSame(
            123,
            $repo->getIblock()
        );
    }

    public function testSetIblockWithNullValue()
    {
        $repo = $this->getObject();
        $this->setExpectedException('\InvalidArgumentException');
        $repo->setIblock(null);
    }

    public function testSetIblockWithEmptyStringValue()
    {
        $repo = $this->getObject();
        $this->setExpectedException('\InvalidArgumentException');
        $repo->setIblock('');
    }

    public function testSetIblockWithEmptyIntValue()
    {
        $repo = $this->getObject();
        $this->setExpectedException('\InvalidArgumentException');
        $repo->setIblock(-1);
    }

    public function testGetIblockWithNotSetIblock()
    {
        $repo = $this->getObject();
        $this->setExpectedException('\UnexpectedValueException');
        $repo->getIblock();
    }

    public function testGetFieldsDescription()
    {
        $repo = $this->getObject();
        $iblockHelper = $this->getMockBuilder('\marvin255\bxar\iblock\IIblockHelper')->getMock();
        $iblockHelper->expects($this->once())
            ->method('getIblockFields')
            ->with($this->equalTo(321))
            ->will($this->returnValue([
                'CODE' => [
                    'type' => 'string',
                    'label' => 'code',
                ],
                'PROPERTY_TEST' => [
                    'type' => 'string',
                    'label' => 'test',
                ],
            ]));
        $repo->setIblockHelper($iblockHelper);
        $repo->setIblock(321);
        $repo->getFieldsDescription();
        $repo->getFieldsDescription();
        $repo->getFieldsDescription();

        $this->assertSame(
            [
                'code' => [
                    'type' => 'string',
                    'label' => 'code',
                ],
                'property_test' => [
                    'type' => 'string',
                    'label' => 'test',
                ],
            ],
            $repo->getFieldsDescription()
        );
    }

    public function testGetFieldsDescriptionWithEmptyFieldsList()
    {
        $repo = $this->getObject();
        $iblockHelper = $this->getMockBuilder('\marvin255\bxar\iblock\IIblockHelper')->getMock();
        $iblockHelper->expects($this->once())
            ->method('getIblockFields')
            ->with($this->equalTo(456))
            ->will($this->returnValue(null));
        $repo->setIblockHelper($iblockHelper);
        $repo->setIblock(456);
        $repo->getFieldsDescription();
        $repo->getFieldsDescription();
        $repo->getFieldsDescription();

        $this->assertSame(
            [],
            $repo->getFieldsDescription()
        );
    }

    public function testGetField()
    {
        $repo = $this->getObject();
        $field = $this->getMockBuilder('\marvin255\bxar\IField')->getMock();
        $iblockHelper = $this->getMockBuilder('\marvin255\bxar\iblock\IIblockHelper')->getMock();
        $iblockHelper->expects($this->once())
            ->method('getIblockFields')
            ->with($this->equalTo(789))
            ->will($this->returnValue([
                'CODE' => [
                    'type' => 'string',
                    'label' => 'code',
                ],
                'PROPERTY_TEST' => [
                    'type' => 'string',
                    'label' => 'test',
                    'params' => [
                        'test' => 1,
                    ],
                ],
            ]));
        $iblockHelper->expects($this->once())
            ->method('createField')
            ->with($this->equalTo([
                'type' => 'string',
                'label' => 'test',
                'params' => [
                    'test' => 1,
                ],
            ]))->will($this->returnValue($field));
        $repo->setIblockHelper($iblockHelper);
        $repo->setIblock(789);
        $repo->getField('property_test');
        $repo->getField('PROPERTY_TEST');

        $this->assertSame(
            $field,
            $repo->getField('property_test')
        );
    }

    public function getObject()
    {
        return new \marvin255\bxar\iblock\Repo();
    }
}
