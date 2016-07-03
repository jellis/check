<?php

namespace Jellis\Check\Tests\Stubs;

use Jellis\Check\Roles\Base;

class Role extends Base
{

    protected $permissions = [
        'test' => [
            'index', 'view:foo', 'create', 'store:own'
        ]
    ];

}