<?php

namespace Hypernode\DeployConfiguration\Command\Build\Shopware6;

use function Deployer\test;
use function Deployer\run;
use Hypernode\DeployConfiguration\Command\Command;

class SystemInstall extends Command
{
    /**
     * SystemInstall constructor.
     */
    public function __construct()
    {
        parent::__construct(function () {
            run('{{bin/php}} bin/console system:install --force');
        });
    }
}
