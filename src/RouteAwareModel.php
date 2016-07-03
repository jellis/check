<?php

namespace Jellis\Check;

use Illuminate\Database\Eloquent\Model;

abstract class RouteAwareModel extends Model
{

    /**
     * The current route for the model
     *
     * @var string
     */
    protected static $_route;

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new Scope);
    }

    /**
     * Get the route currently set on the model
     *
     * @return bool
     */
    public static function getRoute()
    {
        return static::$_route;
    }

    /**
     * Set the route for the model
     *
     * @param $route
     */
    public static function setRoute($route)
    {
        static::$_route = $route;
    }

}