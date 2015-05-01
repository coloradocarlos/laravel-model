<?php

require_once 'tests/stubs/ModelFillableStub.php';
require_once 'tests/stubs/ModelStub.php';

class ModelFillableTest extends PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $arr = array('a' => 'one', 'b' => 'two', 'c' => 'three');
        $o = new ModelFillableStub($arr);
        $this->assertEquals('one', $o->a);
        $this->assertEquals('two', $o->b);
        $this->assertEquals('three', $o->c);
        $this->assertNull($o->d);
    }

    public function testUnfillable1()
    {
        // d should be ignored
        $arr = array('a' => 'one', 'b' => 'two', 'c' => 'three', 'd' => 'four');
        $o = new ModelFillableStub($arr);
        $this->assertEquals('one', $o->a);
        $this->assertEquals('two', $o->b);
        $this->assertEquals('three', $o->c);
        $this->assertNull($o->d);
    }

    public function testGuardedCons()
    {
        // e should be guarded
        $arr = array('e' => 'five');
        $o = new ModelFillableStub($arr);
        $this->assertNull($o->e);
    }

    public function testGuardedFunc()
    {
        // e should be guarded
        $o = new ModelFillableStub();
        $this->assertFalse($o->isGuarded('a'));
        $this->assertTrue($o->isGuarded('e'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Mass assignment not supported for: a
     */
    public function testStubNotFillable()
    {
        $arr = array('a' => 'one');
        // Model does not allow mass assignment
        $o = new ModelStub($arr);
    }

    public function testRemap1()
    {
        $arr = array('a' => 'one', 'b' => 'two', 'ooBarFay' => 'three', 'amplePropsay' => 'four');
        $o = new ModelFillableStub($arr);

        $this->assertEquals('one', $o->a);
        $this->assertEquals('two', $o->b);
        $this->assertEquals('three', $o->fooBar);
        $this->assertEquals('four', $o->sampleProp);
        $this->assertNull($o->ooBarFay);
        $this->assertNull($o->amplePropsay);
    }

    public function testRemap2()
    {
        $arr = array('a' => 'one', 'b' => 'two', 'fooBar' => 'three', 'sampleProp' => 'four');
        $o = new ModelFillableStub($arr);

        $this->assertEquals('one', $o->a);
        $this->assertEquals('two', $o->b);
        $this->assertEquals('three', $o->fooBar);
        $this->assertEquals('four', $o->sampleProp);
        $this->assertNull($o->ooBarFay);
        $this->assertNull($o->amplePropsay);
    }
}
