<?php

namespace Hypernode\DeployConfiguration\ApplicationTemplate;

use Hypernode\DeployConfiguration\Configuration;

class Magento1 extends Configuration
{
    public function __construct()
    {
        parent::__construct();

        $this->initializeDefaultConfiguration();
    }

    /**
     * Initialize defaults
     */
    private function initializeDefaultConfiguration(): void
    {
        $this->setRecipe('magento1');

        $this->addBuildTask('deploy:vendors');
        $this->addDeployTask('magento1:maintenance_mode:enable');
        $this->addDeployTask('magento1:sys:setup:run');
        $this->addDeployTask('magento1:cache:flush');

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
