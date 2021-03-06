<?php
require_once 'tests/stubs/ModelStub.php';

class ModelTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        // Model allows all values to mass assignable
        Jenssegers\Model\Model::unguard();
    }

    public function testAttributeManipulation()
    {
        $model = new ModelStub;
        $model->name = 'foo';

        $this->assertEquals('foo', $model->name);
        $this->assertTrue(isset($model->name));
        unset($model->name);
        $this->assertEquals(null, $model->name);
        $this->assertFalse(isset($model->name));

        $model['name'] = 'foo';
        $this->assertTrue(isset($model['name']));
        unset($model['name']);
        $this->assertFalse(isset($model['name']));
    }

    public function testConstructor()
    {
        $model = new ModelStub(array('name' => 'john'));
        $this->assertEquals('john', $model->name);
    }

    public function testNewInstanceWithAttributes()
    {
        $model = new ModelStub;
        $instance = $model->newInstance(array('name' => 'john'));

        $this->assertInstanceOf('ModelStub', $instance);
        $this->assertEquals('john', $instance->name);
    }

    public function testHidden()
    {
        $model = new ModelStub;
        $model->password = 'secret';

        $attributes = $model->attributesToArray();
        $this->assertFalse(isset($attributes['password']));
        $this->assertEquals(array('password'), $model->getHidden());
    }

    public function testVisible()
    {
        $model = new ModelStub;
        $model->setVisible(array('name'));
        $model->name = 'John Doe';
        $model->city = 'Paris';

        $attributes = $model->attributesToArray();
        $this->assertEquals(array('name' => 'John Doe'), $attributes);
    }

    public function testToArray()
    {
        $model = new ModelStub;
        $model->name = 'foo';
        $model->bar = null;
        $model->password = 'password1';
        $model->setHidden(array('password'));
        $array = $model->toArray();

        $this->assertTrue(is_array($array));
        $this->assertEquals('foo', $array['name']);
        $this->assertFalse(isset($array['password']));
        $this->assertEquals($array, $model->jsonSerialize());
    }

    public function testToJson()
    {
        $model = new ModelStub;
        $model->name = 'john';
        $model->foo = 10;

        $object = new stdClass;
        $object->name = 'john';
        $object->foo = 10;

        $this->assertEquals(json_encode($object), $model->toJson());
        $this->assertEquals(json_encode($object), (string) $model);
    }

    public function testMutator()
    {
        $model = new ModelStub;
        $model->list_items = array('name' => 'john');
        $this->assertEquals(array('name' => 'john'), $model->list_items);
        $attributes = $model->getAttributes();
        $this->assertEquals(json_encode(array('name' => 'john')), $attributes['list_items']);

        $birthday = strtotime('245 months ago');

        $model = new ModelStub;
        $model->birthday = '245 months ago';

        $this->assertEquals(date('Y-m-d', $birthday), $model->birthday);
        $this->assertEquals(20, $model->age);
    }

    public function testToArrayUsesMutators()
    {
        $model = new ModelStub;
        $model->list_items = array(1, 2, 3);
        $array = $model->toArray();

        $this->assertEquals(array(1, 2, 3), $array['list_items']);
    }

    public function testReplicate()
    {
        $model = new ModelStub;
        $model->name = 'John Doe';
        $model->city = 'Paris';

        $clone = $model->replicate();
        $this->assertEquals($model, $clone);
        $this->assertEquals($model->name, $clone->name);
    }

    public function testAppends()
    {
        $model = new ModelStub;
        $array = $model->toArray();
        $this->assertFalse(isset($array['test']));

        $model = new ModelStub;
        $model->setAppends(array('test'));
        $array = $model->toArray();
        $this->assertTrue(isset($array['test']));
        $this->assertEquals('test', $array['test']);
    }

    public function testArrayAccess()
    {
        $model = new ModelStub;
        $model->name = 'John Doen';
        $model['city'] = 'Paris';

        $this->assertEquals($model->name, $model['name']);
        $this->assertEquals($model->city, $model['city']);
    }

    public function testSerialize()
    {
        $model = new ModelStub;
        $model->name = 'john';
        $model->foo = 10;

        $serialized = serialize($model);
        $this->assertEquals($model, unserialize($serialized));
    }

    public function testCasts()
    {
        $model = new ModelStub;
        $model->birthday = '1989/08/19';
        $model->score = '0.34';

        $this->assertTrue(is_int($model->age));
        $this->assertTrue(is_float($model->score));
    }

    public function testAttributesToArrayWithCast()
    {
        $model = new ModelStub;
        // Model has a cast for isStudent
        $model->setVisible(array('isStudent'));
        $model->isStudent = true;
        $this->assertNotNull($model->isStudent);
        $this->assertTrue($model->isStudent);

        // Checks that $mutatedAttributes is set internally
        $attributes = $model->attributesToArray();
    }
}
