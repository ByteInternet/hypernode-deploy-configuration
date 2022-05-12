<?php

namespace Hypernode\DeployConfiguration\Command\Deploy\Magento2;

use Hypernode\DeployConfiguration\Command\DeployCommand;
use Hypernode\DeployConfiguration\ServerRole;

class SetupUpgrade extends DeployCommand
{
    /**
     * DeployModeSet constructor.
     */
    public function __construct()
    {
        parent::__construct('{{bin/php}} bin/magento setup:upgrade --no-interaction --keep-generated');
        $this->setServerRoles([ServerRole::APPLICATION_FIRST]);
    }
}
