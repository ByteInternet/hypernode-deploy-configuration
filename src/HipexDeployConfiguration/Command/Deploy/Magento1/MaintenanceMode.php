<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration\Command\Deploy\Magento1;

use function Deployer\has;
use function Deployer\run;
use function Deployer\test;
use HipexDeployConfiguration\Command\DeployCommand;
use HipexDeployConfiguration\ServerRole;

class MaintenanceMode extends DeployCommand
{
    /**
     * MaintenanceMode constructor.
     */
    public function __construct()
    {
        parent::__construct(function() {
            if (has('previous_release') && test('[ -f {{previous_release}}/{{public_folder}}/.maintenance.flag ]')) {
                run('touch {{public_folder}}/.maintenance.flag');
            }
        });
        $this->setServerRoles([ServerRole::APPLICATION]);
    }
}
