<?php

namespace marvin255\bxar\tests\bxar\repo;

class RepoTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorWithWrongModelName()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $this->setExpectedException(
           '\marvin255\bxar\repo\Exception',
           'Wrong model name: test'
        );
        $repo = new \marvin255\bxar\repo\Repo($provider, 'test');
    }

    public function testOne()
    {
        $fields = ['test' => [1, 2, 3]];
        $field = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->method('createFieldHandler')
            ->will($this->returnValue($field));
        $provider->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query), $this->equalTo($fields))
            ->will($this->returnValue([['test' => 1]]));
        $model = get_class(
            $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')->getMock()
        );
        $repo = new \marvin255\bxar\repo\Repo($provider, $model);
        $this->assertInstanceOf(
            $model,
            $repo->one($query),
            'Repo must create models with class that was set in constructor'
        );
    }

    public function testOneWithEmptyResponse()
    {
        $fields = ['test' => [1, 2, 3]];
        $field = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->method('createFieldHandler')
            ->will($this->returnValue($field));
        $provider->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query), $this->equalTo($fields))
            ->will($this->returnValue([]));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->assertSame(
            null,
            $repo->one($query),
            'Repo must return null if nothing found'
        );
    }

    public function testOneWithExceptionInProvider()
    {
        $fields = ['test' => [1, 2, 3]];
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->method('search')
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while searching: test'
        );
        $repo->one($query);
    }

    public function testOneWithWrongFieldInstance()
    {
        $fields = ['test' => [1, 2, 3]];
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->method('createFieldHandler')
            ->will($this->returnValue('test'));
        $provider->method('search')
            ->will($this->returnValue([['test' => 1]]));
        $model = get_class(
            $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')->getMock()
        );
        $repo = new \marvin255\bxar\repo\Repo($provider, $model);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Field must be an instance of \marvin255\bxar\model\FieldInterface: test'
        );
        $repo->one($query);
    }

    public function testAll()
    {
        $fields = ['test' => [1, 2, 3]];
        $field = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->method('createFieldHandler')
            ->will($this->returnValue($field));
        $provider->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query), $this->equalTo($fields))
            ->will($this->returnValue([['test' => 1], ['test' => 1]]));
        $model = get_class(
            $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')->getMock()
        );
        $repo = new \marvin255\bxar\repo\Repo($provider, $model);
        $res = $repo->all($query);
        $this->assertCount(
            2,
            $res,
            'Repo must create same quantity of models as provider returns'
        );
        foreach ($res as $item) {
            $this->assertInstanceOf(
                $model,
                $item,
                'Repo must create models with class that was set in constructor'
            );
        }
    }

    public function testAllWithEmptyResponse()
    {
        $fields = ['test' => [1, 2, 3]];
        $field = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->method('createFieldHandler')
            ->will($this->returnValue($field));
        $provider->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query), $this->equalTo($fields))
            ->will($this->returnValue([]));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->assertSame(
            [],
            $repo->all($query),
            'Repo must return empty array if nothing found'
        );
    }

    public function testAllWithExceptionInProvider()
    {
        $fields = ['test' => [1, 2, 3]];
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->method('search')
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while searching: test'
        );
        $repo->all($query);
    }

    public function testAllWithWrongFieldInstance()
    {
        $fields = ['test' => [1, 2, 3]];
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->method('createFieldHandler')
            ->will($this->returnValue('test'));
        $provider->method('search')
            ->will($this->returnValue([['test' => 1]]));
        $model = get_class(
            $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')->getMock()
        );
        $repo = new \marvin255\bxar\repo\Repo($provider, $model);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Field must be an instance of \marvin255\bxar\model\FieldInterface: test'
        );
        $repo->all($query);
    }

    public function testCount()
    {
        $fields = ['test' => [1, 2, 3]];
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->expects($this->once())
            ->method('count')
            ->with($this->equalTo($query), $this->equalTo($fields))
            ->will($this->returnValue('123'));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->assertSame(
            123,
            $repo->count($query),
            'Repo must return count result from provider'
        );
    }

    public function testCountWithExceptionInProvider()
    {
        $fields = ['test' => [1, 2, 3]];
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->method('count')
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while counting: test'
        );
        $repo->count($query);
    }

    public function testSave()
    {
        $fields = ['test' => [1, 2, 3]];
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->expects($this->once())
            ->method('save')
            ->with($this->equalTo($model), $this->equalTo($fields))
            ->will($this->returnValue('123'));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->assertSame(
            true,
            $repo->save($model),
            'Repo must send model to provider for saving'
        );
    }

    public function testSaveWithExceptionInProvider()
    {
        $fields = ['test' => [1, 2, 3]];
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->method('save')
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while saving: test'
        );
        $repo->save($model);
    }

    public function testDelete()
    {
        $fields = ['test' => [1, 2, 3]];
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($model), $this->equalTo($fields))
            ->will($this->returnValue(null));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->assertSame(
            false,
            $repo->delete($model),
            'Repo must send model to provider for deleting'
        );
    }

    public function testDeleteWithExceptionInProvider()
    {
        $fields = ['test' => [1, 2, 3]];
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->method('delete')
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while deleting: test'
        );
        $repo->delete($model);
    }

    public function testGetFieldsDescription()
    {
        $fields = [
            'test' => [1, 2, 3],
            'test1' => ['3', '2', '1'],
        ];
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->assertSame(
            $fields,
            $repo->getFieldsDescription(),
            'Repo must return fields description from provider'
        );
    }

    public function testGetFieldsDescriptionNameEncoding()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will(
                $this->returnValue([
                    ' TeS*/?t    ' => 1,
                    't()e_st   2' => 2,
                ])
            );
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->assertSame(
            [
                'tes___t' => 1,
                't__e_st___2' => 2,
            ],
            $repo->getFieldsDescription(),
            'Repo must process fields\' names before return'
        );
    }

    public function testGetFieldsDescriptionLazyLoad()
    {
        $fields = [
            'test' => [1, 2, 3],
            'test1' => ['3', '2', '1'],
        ];
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $repo->getFieldsDescription();
        $repo->getFieldsDescription();
        $repo->getFieldsDescription();
    }

    public function testGetFieldsDescriptionWithExceptionInProvider()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->setExpectedException(
           '\marvin255\bxar\repo\Exception',
           'Error while getting fields\' descriptions: test'
        );
        $repo->getFieldsDescription();
    }

    public function testGetFieldsDescriptionWithEmptyProviderResult()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue([]));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->setExpectedException(
           '\marvin255\bxar\repo\Exception',
           'Fields list must be an array instance'
        );
        $repo->getFieldsDescription();
    }

    public function testGetFieldsDescriptionWithWrongProviderResult()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue('test'));
        $repo = new \marvin255\bxar\repo\Repo($provider);
        $this->setExpectedException(
           '\marvin255\bxar\repo\Exception',
           'Fields list must be an array instance'
        );
        $repo->getFieldsDescription();
    }

    public function testInit()
    {
        $fields = [
            'test' => [1, 2, 3],
            'test2' => ['1', '3', '2'],
        ];
        $field = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $provider->expects($this->exactly(2))
            ->method('createFieldHandler')
            ->will($this->returnValue($field));
        $model = get_class(
            $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')->getMock()
        );
        $repo = new \marvin255\bxar\repo\Repo($provider, $model);
        $res = $repo->init();
        $this->assertInstanceOf(
            $model,
            $res,
            'Repo must create models with class that was set in constructor'
        );
    }
}
