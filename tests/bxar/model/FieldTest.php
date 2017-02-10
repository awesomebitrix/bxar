<?php

namespace marvin255\bxar\tests\bxar\model;

class FieldTest extends \PHPUnit_Framework_TestCase
{
    public function testSetName()
    {
        $field = new \marvin255\bxar\model\Field();
        $this->assertSame(
            $field,
            $field->setName('123'),
            'Field setName method must return instance of field'
        );
        $this->assertSame(
            '123',
            $field->getName(),
            'Field getName method must return value that was set by setName'
        );
    }

    public function testSetValue()
    {
        $field = new \marvin255\bxar\model\Field();
        $this->assertSame(
            $field,
            $field->setValue('123'),
            'Field setValue method must return instance of field'
        );
        $this->assertSame(
            '123',
            $field->getValue(),
            'Field getValue method must return value that was set by setField'
        );
    }

    public function testSetModel()
    {
        $field = new \marvin255\bxar\model\Field();
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $this->assertSame(
            $field,
            $field->setModel($model),
            'Field setModel method must return instance of field'
        );
        $this->assertSame(
            $model,
            $field->getModel(),
            'Field getModel method must return value that was set by setModel'
        );
    }

    public function testGetParams()
    {
        $params = [
            'test1' => [111, '222'],
            'test2' => [333, '444'],
        ];
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')
            ->getMock();
        $repo->method('getFieldsDescription')
            ->will($this->returnValue($params));
        $repo->method('encode')
            ->will($this->returnArgument(0));
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $model->method('getRepo')
            ->will($this->returnValue($repo));
        $field = new \marvin255\bxar\model\Field();
        $this->assertSame(
            $params['test1'],
            $field->setModel($model)->setName('test1')->getParams(),
            'Field should get it\'s params from repo'
        );
    }

    public function testGetParam()
    {
        $params = [
            'test1' => [111, '222'],
            'test2' => [333, 'test' => '444'],
        ];
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')
            ->getMock();
        $repo->method('getFieldsDescription')
            ->will($this->returnValue($params));
        $repo->method('encode')
            ->will($this->returnArgument(0));
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $model->method('getRepo')
            ->will($this->returnValue($repo));
        $field = new \marvin255\bxar\model\Field();
        $this->assertSame(
            $params['test2']['test'],
            $field->setModel($model)->setName('test2')->getParam('test'),
            'Field should get it\'s params from repo'
        );
    }

    public function testAddError()
    {
        $field = new \marvin255\bxar\model\Field();
        $this->assertSame(
            $field,
            $field->addError('123'),
            'Field setValue method must return instance of field'
        );
        $field->addError('321');
        $this->assertSame(
            ['123', '321'],
            $field->getErrors(),
            'Field addError should add all errors to field'
        );
    }

    public function testClearErrors()
    {
        $field = new \marvin255\bxar\model\Field();
        $field->addError('321');
        $field->addError('123');
        $this->assertSame(
            $field,
            $field->clearErrors(),
            'Field clearErrors method must return instance of field'
        );
        $this->assertSame(
            [],
            $field->getErrors(),
            'Field clearErrors should remove all errors from field'
        );
    }
}
