<?php

namespace Hypernode\DeployConfiguration\Command\Deploy\Shopware6;

use Hypernode\DeployConfiguration\Command\DeployCommand;
use Hypernode\DeployConfiguration\ServerRole;
use function Deployer\test;
use function Deployer\run;

class ThemeCompile extends DeployCommand
{

    /**
     * CompileTheme constructor.
     */
    public function __construct()
    {
        parent::__construct('{{bin/php}} bin/console theme:compile');
        $this->setServerRoles([ServerRole::APPLICATION_FIRST]);
    }
}
