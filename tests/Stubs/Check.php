<?php

namespace Jellis\Check\Tests\Stubs;

use Jellis\Check\Check as Original;

class Check extends Original
{

    protected $default = 'RoleDefault';

    protected $rolesLocation = ''; // The stubs directory, based on the namespace

    protected $rolesNamespace = __NAMESPACE__; // So we load roles out of the stubs directory
    
}