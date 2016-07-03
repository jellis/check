<?php

namespace Jellis\Check\Facades;

use Jellis\Check\Check as AuthoriseCheck;
use Illuminate\Support\Facades\Facade;

class Check extends Facade
{

    protected static function getFacadeAccessor()
    {
        return AuthoriseCheck::class;
    }

}