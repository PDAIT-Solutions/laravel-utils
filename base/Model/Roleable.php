<?php

namespace PDAit\Base\Model;


/**
 * Trait Roleable
 *
 * @package PDAit\Model
 */
trait Roleable
{
    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role)
    {
        if (!is_iterable($this->roles)) {
            return false;
        }
        return in_array($role, $this->roles);
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    public function isGranted(string $role)
    {
        if ( ! is_iterable($this->roles)) {
            return false;
        }

        if (in_array($role, $this->roles)) {
            return true;
        }
        $roles = $this->getAllRolesUppers($role);
        $c = array_intersect($this->roles,$roles);

        return count($c) > 0;
    }

    /**
     * @param string     $role
     * @param array|null $roles
     *
     * @return array|null
     */
    public function getAllRolesUppers($roleOrRoles, ?array $roles = [])
    {
        $uppers = [];

        foreach (self::ROLES_HIERARCHY as $upper => $lowers) {
            if ( ! is_array($roleOrRoles) && in_array($roleOrRoles, $lowers)) {
                $roles[] = $upper;
                $uppers[] = $upper;
            } elseif (is_array($roleOrRoles)) {
                foreach ($roleOrRoles as $role) {
                    if ( ! is_array($role) && in_array($role, $lowers)) {
                        $roles[] = $upper;
                        $uppers[] = $upper;
                    }
                }
            }
        }

        if (count($uppers) > 0) {
            return $this->getAllRolesUppers($uppers, $roles);
        }

        return $roles;
    }
}
