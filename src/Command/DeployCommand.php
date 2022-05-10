<?php

/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace Hypernode\DeployConfiguration\Command;

use Hypernode\DeployConfiguration\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\ServerRoleConfigurableTrait;

class DeployCommand extends Command implements ServerRoleConfigurableInterface
{
    use ServerRoleConfigurableTrait;
}
