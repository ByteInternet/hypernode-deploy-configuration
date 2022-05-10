<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration\Command\Deploy\Magento2;

use HipexDeployConfiguration\Command\DeployCommand;
use HipexDeployConfiguration\ServerRole;

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
