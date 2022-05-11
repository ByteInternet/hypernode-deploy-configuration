<?php

namespace Hypernode\DeployConfiguration\Command\Deploy\Shopware6;

use Hypernode\DeployConfiguration\Command\DeployCommand;
use Hypernode\DeployConfiguration\ServerRole;

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
