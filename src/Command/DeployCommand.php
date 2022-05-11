<?php

/**
 * @author Hypernode
 * @copyright Copyright (c) Hypernode
 */

namespace Hypernode\DeployConfiguration\Command;

use Hypernode\DeployConfiguration\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\ServerRoleConfigurableTrait;

class DeployCommand extends Command implements ServerRoleConfigurableInterface
{
    use ServerRoleConfigurableTrait;
}
