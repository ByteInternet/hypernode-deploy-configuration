<?php

/**
 * @author Hypernode
 * @copyright Copyright (c) Hypernode
 */

namespace Hypernode\DeployConfiguration\Command\Deploy\Magento1;

use Hypernode\DeployConfiguration\Command\DeployCommand;
use Hypernode\DeployConfiguration\ServerRole;

class MagerunCacheFlush extends DeployCommand
{
    /**
     * MagerunCacheFlush constructor.
     */
    public function __construct()
    {
        parent::__construct('magerun cache:flush');
        $this->setServerRoles([ServerRole::APPLICATION_FIRST]);
    }
}
