<?php

namespace Hypernode\DeployConfiguration\Command;

use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableTrait;

class DeployCommand extends Command implements ServerRoleConfigurableInterface
{
    use ServerRoleConfigurableTrait;
}
