<?php

namespace Jellis\Check\Tests\Unit;

use Jellis\Check\Tests\Stubs\Role;
use Jellis\Check\Tests\TestCase;

class RoleTest extends TestCase
{

    public function test_it_flattens_permissions()
    {
        $role = new Role();

        $compare = [
            'test.index' => false,
            'test.view' => 'foo',
            'test.create' => false,
            'test.store' => 'own',
        ];

        $flattened = $this->accessPrivateProperty($role, 'flattened');

        $this->assertEquals($compare, $flattened);
    }

    public function test_it_allows_permission_without_scope()
    {
        $role = new Role();

        $this->assertTrue($role->check('test.index'));
    }

    public function test_it_allows_permission_with_scope()
    {
        // test.view has 'foo' scope
        $role = new Role();

        $this->assertTrue($role->check('test.view'));
    }

    public function test_it_denies_permission()
    {
        $role = new Role();

        $this->assertFalse($role->check('test.test'));
    }

    public function test_it_has_correct_scope()
    {
        $role = new Role();

        $this->assertEquals('foo', $role->scope('test.view'));
        $this->assertFalse($role->scope('test.index'));
    }



}