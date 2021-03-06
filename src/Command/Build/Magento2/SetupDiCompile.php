<?php

namespace Hypernode\DeployConfiguration\Command\Build\Magento2;

use function Deployer\test;
use function Deployer\run;
use Hypernode\DeployConfiguration\Command\Command;

class SetupDiCompile extends Command
{
    /**
     * DeployModeSet constructor.
     */
    public function __construct()
    {
        parent::__construct(function () {
            if (!test('[ -d generated/code ]')) {
                run('{{bin/php}} bin/magento setup:di:compile', ['timeout' => 3600]);
            }
        });
    }
}
