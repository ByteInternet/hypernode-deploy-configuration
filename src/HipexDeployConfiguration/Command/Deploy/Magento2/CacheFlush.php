<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration\Command\Deploy\Magento2;

use HipexDeployConfiguration\Command\DeployCommand;
use HipexDeployConfiguration\ServerRole;

class CacheFlush extends DeployCommand
{
    /**
     * DeployModeSet constructor.
     */
    public function __construct()
    {
        parent::__construct('{{bin/php}} bin/magento cache:flush');
        $this->setServerRoles([ServerRole::APPLICATION_FIRST]);
    }
}
