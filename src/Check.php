<?php

namespace Jellis\Check;

use Illuminate\Database\Eloquent\Model;
use Jellis\Check\Roles\Base;
use Illuminate\Console\AppNamespaceDetectorTrait;

class Check
{
    use AppNamespaceDetectorTrait;

    /**
     * @var Base
     */
    public $role;

    /**
     * Default role
     *
     * @var string
     */
    protected $default = 'Guest';

    /**
     * The low-level location for the role classes
     *
     * @var string
     */
    protected $rolesLocation = 'Roles';

    /**
     * The namespace of the directory ABOVE the roles directory
     *
     * @var string
     */
    protected $rolesNamespace = false;

    /**
     * Flatten out the permissions
     *
     * @param null $role
     */
    public function __construct($role = null)
    {
        $this->setRolesNamespace();

        $this->setRole($role);
    }

    /**
     * Set the role for this request
     *
     * @param $role
     * @return mixed
     */
    public function setRole($role)
    {
        $this->role = $this->getRoleObject($role);
    }

    /**
     * The role object
     *
     * @return Base
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Return a new role class
     *
     * @param $role
     * @return mixed
     */
    private function getRoleObject($role)
    {
        if (class_exists($this->getNamespacedRole($role))) {
            $roleClass = $this->getNamespacedRole($role);
        } else {
            $roleClass = $this->getNamespacedRole($this->default);
        }

        return new $roleClass;
    }

    /**
     * Full qualified location of the roles
     *
     * @param $role
     * @return string
     */
    private function getNamespacedRole($role)
    {
        return $this->getRolesNamespace() . $this->rolesLocation . '\\' . ucfirst(camel_case($role));
    }

    /**
     * Fire off the check against the role
     *
     * @param $action
     * @param Model|null $model
     * @return mixed
     */
    public function can($action, Model $model = null)
    {
        return $this->role->check($action, $model);
    }

    /**
     * Set the namespace for the directory above the roles directory
     */
    private function setRolesNamespace()
    {
        if ($this->getRolesNamespace() === false) {
            $this->rolesNamespace = $this->getAppNamespace();
        }
    }

    /**
     * Get the namespace for the directory above the roles directory
     *
     * @return string
     */
    private function getRolesNamespace()
    {
        return $this->rolesNamespace;
    }

}