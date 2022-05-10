<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration\Command\Deploy\Magento1;

use HipexDeployConfiguration\Command\DeployCommand;
use HipexDeployConfiguration\ServerRole;

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
