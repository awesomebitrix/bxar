<?php

namespace marvin255\bxar\tests\bxar\repo;

class RepoTest extends \PHPUnit_Framework_TestCase
{
    public function testGetModelName()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->assertSame(
            $modelName,
            $repo->getModelName(),
            'Repo must return same model name that was set in second constructor param'
        );
    }

    public function testGetModelNameWithException()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $modelName = 'wrong_model_name';
        $this->setExpectedException(
            'InvalidArgumentException',
            'Wrong model name: '.$modelName
        );
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
    }

    public function testGetProvider()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->assertSame(
            $provider,
            $repo->getProvider(),
            'Repo must return same provider object name that was set in first constructor param'
        );
    }

    public function testGetFieldsDescription()
    {
        $fields = [
            '   TEsT ' => [1, 2, 3],
            'test1' => ['3', '2', '1'],
        ];
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $repo->getFieldsDescription();
        $this->assertSame(
            ['test' => [1, 2, 3], 'test1' => ['3', '2', '1']],
            $repo->getFieldsDescription(),
            'Repo must return fields description from provider unchanged'
        );
    }

    public function testGetFieldsDescriptionWithExceptionInProvider()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('getFieldsDescription')
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while getting fields description: test'
        );
        $repo->getFieldsDescription();
    }

    public function testGetFieldsDescriptionWithWrongProviderResponse()
    {
        $fields = 12;
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('getFieldsDescription')
            ->will($this->returnValue($fields));
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Field descriptions returned by provider must be an array instance'
        );
        $repo->getFieldsDescription();
    }

    public function testCreateFieldHandler()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $modelName = get_class(
            $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')->getMock()
        );
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $handler = $this->getMockBuilder('\marvin255\bxar\model\FieldInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('getFieldsDescription')
            ->will($this->returnValue(['test' => ['fieldData']]));
        $provider->expects($this->once())
            ->method('createFieldHandler')
            ->with(
                $this->equalTo('test'),
                $this->equalTo(['fieldData']),
                $this->equalTo($repo)
            )->will($this->returnValue($handler));
        $this->assertSame(
            $handler,
            $repo->createFieldHandler('    TeSt '),
            'Repo must create field handler using provider'
        );
    }

    public function testCreateFieldHandlerWithWrongFieldName()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('getFieldsDescription')
            ->will($this->returnValue(['test' => ['fieldData']]));
        $modelName = get_class(
            $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')->getMock()
        );
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->setExpectedException(
            '\InvalidArgumentException',
            'Field description not found:     TeSt1 '
        );
        $repo->createFieldHandler('    TeSt1 ');
    }

    public function testCreateFieldHandlerWithExceptionInProvider()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('getFieldsDescription')
            ->will($this->returnValue(['test' => ['fieldData']]));
        $provider->expects($this->once())
            ->method('createFieldHandler')
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $modelName = get_class(
            $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')->getMock()
        );
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while creating field handler: test'
        );
        $repo->createFieldHandler('test');
    }

    public function testCreateFieldHandlerWithWrongHandlerType()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $modelName = get_class(
            $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')->getMock()
        );
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $provider->expects($this->once())
            ->method('getFieldsDescription')
            ->will($this->returnValue(['test' => ['fieldData']]));
        $provider->expects($this->once())
            ->method('createFieldHandler')
            ->with(
                $this->equalTo('test'),
                $this->equalTo(['fieldData']),
                $this->equalTo($repo)
            )->will($this->returnValue('test'));
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while creating field handler: provider returned wrong field object'
        );
        $repo->createFieldHandler('test');
    }

    public function testOne()
    {
        $searchData = [
            ['test' => 1, 'test2' => 2],
            ['test' => 1, 'test2' => 2],
        ];
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query))
            ->will($this->returnValue($searchData));
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $res = $repo->one($query);
        $this->assertInstanceOf(
            $modelName,
            $res,
            'Repo must create models with class that was set in constructor'
        );
    }

    public function testOneWithExceptionWhileSearch()
    {
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query))
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while searching: test'
        );
        $res = $repo->one($query);
    }

    public function testOneWithWrongSearchResult()
    {
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query))
            ->will($this->returnValue(1));
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Provider must return array from search'
        );
        $res = $repo->one($query);
    }

    public function testAll()
    {
        $searchData = [
            ['test' => 1, 'test2' => 2],
            ['test' => 1, 'test2' => 2],
        ];
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query))
            ->will($this->returnValue($searchData));
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $res = $repo->all($query);
        $this->assertCount(
            count($searchData),
            $res,
            'Repo must create same quantity of models as provider returns'
        );
        foreach ($res as $model) {
            $this->assertInstanceOf(
                $modelName,
                $model,
                'Repo must create models with class that was set in constructor'
            );
        }
    }

    public function testAllWithExceptionWhileSearch()
    {
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query))
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while searching: test'
        );
        $res = $repo->all($query);
    }

    public function testAllWithWrongSearchResult()
    {
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query))
            ->will($this->returnValue(1));
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Provider must return array from search'
        );
        $res = $repo->all($query);
    }

    public function testCount()
    {
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('count')
            ->with($this->equalTo($query))
            ->will($this->returnValue('123'));
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $res = $repo->count($query);
        $this->assertSame(
            123,
            $res,
            'Repo must return same count as it\'s provider'
        );
    }

    public function testCountWithExceptionWhileCount()
    {
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $query = $this->getMockBuilder('\marvin255\bxar\query\QueryInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('count')
            ->with($this->equalTo($query))
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while counting: test'
        );
        $res = $repo->count($query);
    }

    public function testSave()
    {
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('save')
            ->with($this->equalTo($model))
            ->will($this->returnValue('0'));
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $res = $repo->save($model);
        $this->assertSame(
            false,
            $res,
            'Repo must return same save result as it\'s provider'
        );
    }

    public function testSaveWithExceptionWhileSave()
    {
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('save')
            ->with($this->equalTo($model))
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while saving: test'
        );
        $res = $repo->save($model);
    }

    public function testDelete()
    {
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($model))
            ->will($this->returnValue('1'));
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $res = $repo->delete($model);
        $this->assertSame(
            true,
            $res,
            'Repo must return same delete result as it\'s provider'
        );
    }

    public function testDeleteWithExceptionWhileDelete()
    {
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $provider->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($model))
            ->will($this->throwException(new \InvalidArgumentException('test')));
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->setExpectedException(
            '\marvin255\bxar\repo\Exception',
            'Error while deleting: test'
        );
        $res = $repo->delete($model);
    }

    public function testInitModel()
    {
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->assertInstanceOf(
            $modelName,
            $repo->initModel(),
            'Repo must create model with class from modelName property'
        );
    }

    public function testEncodeField()
    {
        $provider = $this->getMockBuilder('\marvin255\bxar\repo\ProviderInterface')
            ->getMock();
        $model = $this->getMockBuilder('\marvin255\bxar\model\ModelInterface')
            ->getMock();
        $modelName = get_class($model);
        $repo = new \marvin255\bxar\repo\Repo($provider, $modelName);
        $this->assertSame(
            'test_test',
            $repo->encode(' test TEST    '),
            'Repo must encode field names so that they will be always the same'
        );
    }
}
