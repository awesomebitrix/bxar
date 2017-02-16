<?php

namespace marvin255\bxar\tests\bxar\model;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMagic()
    {
        $attributes = [
            'test1' => $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')->getMock(),
            'test2' => $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')->getMock(),
        ];
        $model = new \marvin255\bxar\model\Model($attributes);
        $this->assertSame(
            $attributes['test2'],
            $model->test2,
            'Model must use __get magic to return it\'s attributes'
        );
    }

    public function testConstructWithWrongParam()
    {
        $attributes = [
            'test1' => $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')->getMock(),
            'test2' => 2,
        ];
        $this->setExpectedException(
            'InvalidArgumentException',
            'test2 attribute object must be an \marvin255\bxar\model\FieldInterface instance'
        );
        $model = new \marvin255\bxar\model\Model($attributes);
    }

    public function testGetAttribute()
    {
        $attributes = [
            'test1' => $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')->getMock(),
            'test2' => $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')->getMock(),
        ];
        $model = new \marvin255\bxar\model\Model($attributes);
        $this->assertSame(
            $attributes['test1'],
            $model->getAttribute('test1'),
            'Model must return attribute object by it\'s name'
        );
    }

    public function testSetAttributesValues()
    {
        $field1 = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $field1->expects($this->once())
            ->method('setValue')
            ->with($this->equalTo('321'));
        $field1->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('321'));
        $field2 = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $field2->expects($this->once())
            ->method('setValue')
            ->with($this->equalTo(123));
        $field2->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue(123));
        $model = new \marvin255\bxar\model\Model([
            'test1' => $field1,
            'test2' => $field2,
        ]);
        $model->setAttributesValues(['test1' => '321', 'test2' => 123]);
        $this->assertSame(
            ['test1' => '321', 'test2' => 123],
            $model->getAttributesValues(),
            'Model must return list of attributes\' values'
        );
    }

    public function testGetAttributesErrors()
    {
        $field1 = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $field1->expects($this->once())
            ->method('getErrors')
            ->will($this->returnValue(['321']));
        $field2 = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $field2->expects($this->once())
            ->method('getErrors')
            ->will($this->returnValue([123]));
        $model = new \marvin255\bxar\model\Model([
            'test1' => $field1,
            'test2' => $field2,
        ]);
        $this->assertSame(
            ['test1' => ['321'], 'test2' => [123]],
            $model->getAttributesErrors(),
            'Model must return list of attributes\' errors'
        );
    }
}
