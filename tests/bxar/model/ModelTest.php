<?php

namespace marvin255\bxar\tests\bxar\model;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    public function testSetMagic()
    {
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')
            ->getMock();
        $repo->method('getFieldsDescription')
            ->will($this->returnValue(['test1' => 11]));
        $repo->method('encode')
            ->will($this->returnArgument(0));
        $model = new \marvin255\bxar\model\Model($repo);
        $field = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $repo->expects($this->once())
            ->method('createFieldHandler')
            ->with($this->equalTo('test1'))
            ->will($this->returnValue($field));
        $this->assertSame(
            $field,
            $model->test1,
            'Model must use php __get magic function for getting fields'
        );
    }

    public function testGetRepo()
    {
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')
            ->getMock();
        $model = new \marvin255\bxar\model\Model($repo);
        $this->assertSame(
            $repo,
            $model->getRepo(),
            'Model must return same repo object that was set in first constructor param'
        );
    }

    public function testSetAttributes()
    {
        $data = ['test1' => 1];
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')
            ->getMock();
        $repo->method('getFieldsDescription')
            ->will($this->returnValue(['test1' => 11]));
        $repo->method('encode')
            ->will($this->returnArgument(0));
        $model = new \marvin255\bxar\model\Model($repo);
        $field = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $field->method('getValue')
            ->will($this->returnValue($data['test1']));
        $repo->expects($this->once())
            ->method('createFieldHandler')
            ->with($this->equalTo('test1'))
            ->will($this->returnValue($field));
        $this->assertSame(
            $model,
            $model->setAttributesValues($data),
            'Model must return self instance from setAttributes'
        );
        $this->assertSame(
            $data,
            $model->getAttributesValues(),
            'Model must return attributes values that was set by setAttributes'
        );
    }

    public function testGetAttributesErrors()
    {
        $errors = ['error 1', 'error 2'];
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')
            ->getMock();
        $repo->method('getFieldsDescription')
            ->will($this->returnValue(['test1' => 11]));
        $repo->method('encode')
            ->will($this->returnArgument(0));
        $model = new \marvin255\bxar\model\Model($repo);
        $field = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $field->method('getErrors')
            ->will($this->returnValue($errors));
        $repo->expects($this->once())
            ->method('createFieldHandler')
            ->with($this->equalTo('test1'))
            ->will($this->returnValue($field));
        $this->assertSame(
            ['test1' => $errors],
            $model->getAttributesErrors(),
            'Model must return all errors from it\'s fields'
        );
    }

    public function testSave()
    {
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')
            ->getMock();
        $model = new \marvin255\bxar\model\Model($repo);
        $repo->method('save')
            ->with($this->equalTo($model))
            ->will($this->returnValue(false));
        $this->assertSame(
            false,
            $model->save(),
            'Model must pipe save to it\'s repo and return result from repo\'s save'
        );
    }

    public function testDelete()
    {
        $repo = $this->getMockBuilder('\marvin255\bxar\repo\RepoInterface')
            ->getMock();
        $model = new \marvin255\bxar\model\Model($repo);
        $repo->method('delete')
            ->with($this->equalTo($model))
            ->will($this->returnValue(true));
        $this->assertSame(
            true,
            $model->delete(),
            'Model must pipe delete to it\'s repo and return result from repo\'s delete'
        );
    }
}
