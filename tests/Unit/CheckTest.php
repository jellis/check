<?php

namespace Jellis\Check\Tests\Unit;

use Jellis\Check\Tests\Stubs\Check;
use Jellis\Check\Tests\Stubs\Role;
use Jellis\Check\Tests\Stubs\RoleDefault;
use Jellis\Check\Tests\TestCase;

class CheckTest extends TestCase
{

    public function test_it_sets_the_correct_role_object()
    {
        $check = new Check('role');

        $this->assertInstanceOf(Role::class, $check->getRole());
    }

    public function test_it_sets_default_role_on_fallback()
    {
        $check = new Check('clown');

        $this->assertInstanceOf(RoleDefault::class, $check->getRole());
    }

}
