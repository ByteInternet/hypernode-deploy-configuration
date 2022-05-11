<?php

/**
 * @author Hypernode
 * @copyright Copyright (c) Hypernode
 */

namespace Hypernode\DeployConfiguration\Command\Deploy\Magento1;

use Hypernode\DeployConfiguration\Command\DeployCommand;
use Hypernode\DeployConfiguration\ServerRole;

class MagerunSetupRun extends DeployCommand
{
    /**
     * MagerunSetupRun constructor.
     */
    public function __construct()
    {
        parent::__construct('magerun sys:setup:run');
        $this->setServerRoles([ServerRole::APPLICATION_FIRST]);
    }
}
