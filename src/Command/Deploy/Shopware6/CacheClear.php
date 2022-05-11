<?php

namespace Hypernode\DeployConfiguration\Command\Deploy\Shopware6;

use Hypernode\DeployConfiguration\Command\DeployCommand;
use Hypernode\DeployConfiguration\ServerRole;

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
