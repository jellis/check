<?php

namespace Jellis\Check\Tests\Stubs;

use Jellis\Check\RouteAwareModel;
use Illuminate\Database\Eloquent\Builder;

class CustomModel extends RouteAwareModel
{

    public function allowOwnOnly()
    {
        return $this->user_id == \Auth::id();
    }

    public function restricOwnOnly(Builder $builder)
    {
        $builder->where('user_id', \Auth::id());
    }

}