<?php

namespace Jellis\Check;

use Jellis\Check\Check;
use Jellis\Check\Roles\Base;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope as EloquentScope;

class Scope implements EloquentScope
{

    public function apply(Builder $builder, Model $model)
    {
        // Determine if there's a route to be had
        if (!$model->getRoute() && Request::route() && Request::route()->getName()) {

            // Set the route for the model
            $model->setRoute(Request::route()->getName());
        }

        // Get the scope on the current route
        $scope = Check::getRole()->scope($model->getRoute());

        // Check if the model has the necessary scope
        if ($scope && Base::getModelMethod('restrict', $scope, $model)) {
            return $model->{ Base::getModelMethod('restrict', $scope) }($builder);
        }

        return $builder;
    }

}