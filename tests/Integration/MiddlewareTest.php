<?php

namespace Jellis\Check\Tests\Integration;

use Mockery as m;
use Jellis\Check\Tests\TestCase;
use Jellis\Check\Tests\Stubs\Check;
use Jellis\Check\Middleware\Checker;

class MiddlewareTest extends TestCase
{

    public function test_it_is_true()
    {
        $this->assertTrue(true);
    }

    public function test_it_allows_route()
    {
        $middleware = new Checker(new Check('role'));

        $request = m::mock(\Illuminate\Http\Request::class);

        $request->shouldReceive('route')->andReturn(new class {
            public function getName()
            {
                return 'test.index';
            }
        });

        $test = $middleware->handle($request, function($output) {
            return $output;
        });

        $this->assertInstanceOf(get_class($request), $test);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function test_it_denies_route()
    {
        $middleware = new Checker(new Check('role'));

        $request = m::mock(\Illuminate\Http\Request::class);

        $request->shouldReceive('route')->andReturn(new class {
            public function getName()
            {
                return 'test.nothing';
            }
        });

        $middleware->handle($request, function($output) {
            return $output;
        });
    }

}