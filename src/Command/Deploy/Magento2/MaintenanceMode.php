<?php

/**
 * @author Hypernode
 * @copyright Copyright (c) Hypernode
 */

namespace Hypernode\DeployConfiguration\Command\Deploy\Magento2;

use function Deployer\has;
use function Deployer\run;
use function Deployer\test;
use Hypernode\DeployConfiguration\Command\DeployCommand;
use Hypernode\DeployConfiguration\ServerRole;

class MaintenanceMode extends DeployCommand
{
    /**
     * DeployModeSet constructor.
     */
    public function __construct()
    {
        parent::__construct(function () {
            if (has('previous_release') && test('[ -f {{previous_release}}/var/.maintenance.flag ]')) {
                run('touch var/.maintenance.flag');
            }
        });
        $this->setServerRoles([ServerRole::APPLICATION]);
    }
}
