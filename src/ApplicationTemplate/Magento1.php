<?php

namespace Hypernode\DeployConfiguration\ApplicationTemplate;

use Hypernode\DeployConfiguration\Configuration;
use Hypernode\DeployConfiguration\Command;

class Magento1 extends Configuration
{
    /**
     * Magento1 constructor.
     *
     * @param string $gitRepository
     */
    public function __construct(string $gitRepository)
    {
        parent::__construct($gitRepository);

        $this->initializeDefaultConfiguration();
    }

    /**
     * Initialize defaults
     */
    private function initializeDefaultConfiguration(): void
    {
        $this->addBuildCommand(new Command\Build\Composer());
        $this->addDeployCommand(new Command\Deploy\Magento1\MaintenanceMode());
        $this->addDeployCommand(new Command\Deploy\Magento1\MagerunSetupRun());
        $this->addDeployCommand(new Command\Deploy\Magento1\MagerunCacheFlush());

        $this->setSharedFiles([
            'app/etc/local.xml',
            'errors/local.xml',
        ]);

        $this->setSharedFolders([
            'var',
            'media',
            'sitemap',
        ]);
    }
}
