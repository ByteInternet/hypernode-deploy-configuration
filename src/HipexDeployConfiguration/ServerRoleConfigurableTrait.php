<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2020
 */
declare(strict_types = 1);

namespace HipexDeployConfiguration;


trait ServerRoleConfigurableTrait
{
    /**
     * When set the configuration is only used on servers with one of the provided roles.
     *
     * @var string[]
     */
    private $serverRoles = [];

    /**
     * @return string[]
     */
    public function getServerRoles(): array
    {
        return $this->serverRoles;
    }

    /**
     * @param string[] $serverRoles
     * @return $this
     */
    public function setServerRoles(array $serverRoles): self
    {
        $this->serverRoles = [];
        foreach ($serverRoles as $role) {
            $this->addRole($role);
        }
        return $this;
    }

    /**
     * @param string $role
     * @return $this
     */
    public function addRole(string $role): self
    {
        $this->serverRoles[] = $role;
        return $this;
    }
}