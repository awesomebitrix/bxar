<?php

namespace marvin255\bxar\tests\cases;

abstract class Repo extends \PHPUnit_Framework_TestCase
{
    abstract public function getObject();

    public function testGetFieldHandler()
    {
        $repo = $this->getObject();
        $class = get_class($repo);
        $repo = $this->getMockBuilder($class)
            ->setMethods(['createFieldHandler', 'getFieldsDescriptions'])
            ->getMock();
        $fields = ['test' => ['setting0' => 0, 'setting1' => 1]];
        $repo->method('getFieldsDescriptions')->will($this->returnValue($fields));
        $field = $this->getMockBuilder('\marvin255\bxar\IField')->getMock();
        $repo->expects($this->once())
            ->method('createFieldHandler')
            ->will($this->returnValue($field));
        $repo->getFieldHandler('test');
        $this->assertSame(
            $field,
            $repo->getFieldHandler('test')
        );
    }

    public function testGetFieldHandlerWithBadFieldName()
    {
        $repo = $this->getObject();
        $class = get_class($repo);
        $repo = $this->getMockBuilder($class)
            ->setMethods(['getFieldsDescriptions'])
            ->getMock();
        $fields = ['test' => ['setting0' => 0, 'setting1' => 1]];
        $repo->method('getFieldsDescriptions')->will($this->returnValue($fields));
        $this->setExpectedException('\InvalidArgumentException');
        $repo->getFieldHandler('test2');
    }

    public function testGetFieldHandlerWithBadFieldType()
    {
        $repo = $this->getObject();
        $class = get_class($repo);
        $repo = $this->getMockBuilder($class)
            ->setMethods(['createFieldHandler', 'getFieldsDescriptions'])
            ->getMock();
        $fields = ['test' => ['setting0' => 0, 'setting1' => 1]];
        $repo->method('getFieldsDescriptions')->will($this->returnValue($fields));
        $field = $this->getMockBuilder($class)->getMock();
        $repo->expects($this->once())
            ->method('createFieldHandler')
            ->will($this->returnValue($field));
        $this->setExpectedException('\LogicException');
        $repo->getFieldHandler('test');
    }

    public function testGetFieldHandlerForEncodingFieldName()
    {
        $repo = $this->getObject();
        $class = get_class($repo);
        $repo = $this->getMockBuilder($class)
            ->setMethods(['createFieldHandler', 'getFieldsDescriptions'])
            ->getMock();
        $fields = ['test' => ['setting0' => 0, 'setting1' => 1]];
        $repo->method('getFieldsDescriptions')->will($this->returnValue($fields));
        $field = $this->getMockBuilder('\marvin255\bxar\IField')->getMock();
        $repo->expects($this->once())
            ->method('createFieldHandler')
            ->will($this->returnValue($field));
        $repo->getFieldHandler('test');
        $this->assertSame(
            $field,
            $repo->getFieldHandler('     TeSt       ')
        );
    }

    public function testGetFieldsDescriptions()
    {
        $repo = $this->getObject();
        $class = get_class($repo);
        $fields = [
            'test' => 'test1',
            'test2' => 12,
        ];
        $repo = $this->getMockBuilder($class)
            ->setMethods(['loadFieldsDescriptions'])
            ->getMock();
        $repo->expects($this->once())
            ->method('loadFieldsDescriptions')
            ->will($this->returnValue($fields));
        $repo->getFieldsDescriptions();
        $this->assertSame(
            $fields,
            $repo->getFieldsDescriptions()
        );
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
        $class = get_class($this);
        $this->setExpectedException('\InvalidArgumentException');
        $repo->setModelClass($class);
    }

    public function testEncodeFieldName()
    {
        $repo = $this->getObject();
        $this->assertSame(
            'test',
            $repo->encodeFieldName('    test ')
        );
        $this->assertSame(
            'new_test',
            $repo->encodeFieldName(' NEw_TeSt ')
        );
    }
}
