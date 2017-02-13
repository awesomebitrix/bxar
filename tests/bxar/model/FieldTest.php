<?php

namespace marvin255\bxar\tests\bxar\model;

class FieldTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyNameConstructor()
    {
        $this->setExpectedException('InvalidArgumentException', 'Name can not be empty');
        $field = new \marvin255\bxar\model\Field(
            '',
            $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')->getMock()
        );
    }

    public function testGetName()
    {
        $field = new \marvin255\bxar\model\Field(
            'test_name',
            $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')->getMock()
        );
        $this->assertSame(
            'test_name',
            $field->getName(),
            'Field getName method must return value that was set by constructor'
        );
    }

    public function testGetRepo()
    {
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')->getMock();
        $field = new \marvin255\bxar\model\Field('test_name', $repo);
        $this->assertSame(
            $repo,
            $field->getRepo(),
            'Field getRepo method must return value that was set by constructor'
        );
    }

    public function testSetValue()
    {
        $field = new \marvin255\bxar\model\Field(
            'test_name',
            $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')->getMock()
        );
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
        $field = new \marvin255\bxar\model\Field('test1', $repo);
        $this->assertSame(
            $params['test1'],
            $field->getParams(),
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
        $field = new \marvin255\bxar\model\Field('test2', $repo);
        $this->assertSame(
            $params['test2']['test'],
            $field->getParam('test'),
            'Field should get it\'s params from repo'
        );
    }

    public function testAddError()
    {
        $field = new \marvin255\bxar\model\Field(
            'test_name',
            $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')->getMock()
        );
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
        $field = new \marvin255\bxar\model\Field(
            'test_name',
            $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')->getMock()
        );
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

    public function testGetMagic()
    {
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')->getMock();
        $repo->method('getFieldsDescription')
            ->will($this->returnValue(['test_name' => ['test_param' => 1]]));
        $repo->method('encode')
            ->will($this->returnArgument(0));
        $field = new \marvin255\bxar\model\Field('test_name', $repo);
        $field->setValue('test_value');
        $field->addError('test_error');
        $this->assertSame(
            'test_name',
            $field->name,
            'Field __get should return name'
        );
        $this->assertSame(
            'test_value',
            $field->value,
            'Field __get should return value'
        );
        $this->assertSame(
            ['test_error'],
            $field->errors,
            'Field __get should return errors'
        );
        $this->assertSame(
            1,
            $field->test_param,
            'Field __get should return params'
        );
    }

    public function testSetMagic()
    {
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')->getMock();
        $field = new \marvin255\bxar\model\Field('test_name', $repo);
        $field->test = '';
        $field->value = 'test_value';
        $this->assertSame(
            'test_value',
            $field->getValue(),
            'Field __set should set value'
        );
    }
}
