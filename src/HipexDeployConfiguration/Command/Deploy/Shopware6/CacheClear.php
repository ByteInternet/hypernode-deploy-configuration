<?php

namespace HipexDeployConfiguration\Command\Deploy\Shopware6;

use HipexDeployConfiguration\Command\DeployCommand;
use HipexDeployConfiguration\ServerRole;

class CacheClear extends DeployCommand
{
    /**
     * CacheClear constructor.
     */
    public function __construct()
    {
        parent::__construct('{{bin/php}} bin/console cache:clear');
        $this->setServerRoles([ServerRole::APPLICATION_FIRST]);
    }
}
