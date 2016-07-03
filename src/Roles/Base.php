<?php

namespace Jellis\Check\Roles;

use Illuminate\Database\Eloquent\Model;

abstract class Base
{

    /**
     * Multi-dimensional array of allowed actions
     *
     * @var array
     */
    protected $permissions = [];

    /**
     * Flattened array of allowed actions
     *
     * @var array
     */
    protected $flattened = [];

    /**
     * Flatten permissions and set scopes
     */
    public function __construct()
    {
        $this->flattened = $this->flatten($this->permissions);
    }

    /**
     * Check if this permission exists
     *
     * @param $action string
     * @param Model $model
     * @return mixed
     */
    public function check($action, Model $model = null)
    {
        if ($model && $this->scope($action) && static::scopeExists('allow', $this->scope($action), $model)) {
            return $model->{static::getModelMethod('allow', $this->scope($action))}();
        } else if (array_key_exists($action, $this->flattened)) {
            return true;
        }

        return false;
    }

    /**
     * Return the method that will restrict/allow on the relevant model
     *
     * @param $verb string
     * @param $scope string
     * @return string
     */
    public static function getModelMethod($verb, $scope)
    {
        return $verb . ucfirst($scope) . 'Only';
    }

    /**
     * Check if there's a scope provided on the action
     *
     * @param $scope
     * @param Model $model
     * @return bool
     */
    public static function scopeExists($verb, $action, Model $model)
    {
        return method_exists($model, static::getModelMethod($verb, $action));
    }

    /**
     * Determine if there's scope on the current action
     *
     * @param $action
     * @return bool|mixed
     */
    public function scope($action)
    {
        return !!$action && !empty($this->flattened[$action]) ? $this->flattened[$action] : false;
    }

    /**
     * Get the flattened permissions and set scopes on those
     *
     * @param $permissions
     * @param string $prepend
     * @return array
     */
    protected function flatten($permissions, $prepend = '')
    {
        $results = [];

        foreach ($permissions as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, static::flatten($value, $prepend . $key . '.'));
            } else {
                $scopes = explode(':', $value);
                $results[$prepend.array_first($scopes)] = count($scopes) == 2 ? last($scopes) : false;
            }
        }

        return $results;
    }

}