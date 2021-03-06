<?php

namespace Hypernode\DeployConfiguration\Command\Build\Shopware6;

use function Deployer\test;
use function Deployer\run;
use Hypernode\DeployConfiguration\Command\Command;

class ShopwareRecovery extends Command
{
    /**
     * ShopwareRecovery constructor.
     */
    public function __construct()
    {
        parent::__construct(function () {
            run('{{bin/composer}} install -d vendor/shopware/recovery --no-interaction --optimize-autoloader --no-suggest', ['timeout' => 3600]);
        });
    }
}
