<?php

declare(strict_types=1);

namespace Hypernode\DeployConfiguration;


interface ServerRoleConfigurableInterface
{
    /**
     * @param string[] $serverRoles
     * @return $this
     */
    public function setServerRoles(array $serverRoles);

    /**
     * @param string $role
     * @return self
     */
    public function addRole(string $role);

    /**
     * @return string[]
     */
    public function getServerRoles(): array;
}
