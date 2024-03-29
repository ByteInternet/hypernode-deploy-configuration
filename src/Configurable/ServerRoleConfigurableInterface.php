<?php

declare(strict_types=1);

namespace Hypernode\DeployConfiguration\Configurable;


interface ServerRoleConfigurableInterface
{
    /**
     * @param string[] $serverRoles
     * @return $this
     */
    public function setServerRoles(array $serverRoles);

    /**
     * @return self
     */
    public function addRole(string $role);

    /**
     * @return string[]
     */
    public function getServerRoles(): array;
}
