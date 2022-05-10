<?php

namespace HipexDeployConfiguration\Command\Deploy\Shopware6;

use HipexDeployConfiguration\Command\DeployCommand;
use HipexDeployConfiguration\ServerRole;

class AssetInstall extends DeployCommand
{
    /**
     * CacheClear constructor.
     */
    public function __construct()
    {
        parent::__construct('{{bin/php}} bin/console asset:install');
        $this->setServerRoles([ServerRole::APPLICATION_FIRST]);
    }
}
