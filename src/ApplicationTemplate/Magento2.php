<?php

namespace Hypernode\DeployConfiguration\ApplicationTemplate;

use Hypernode\DeployConfiguration\Configuration;

class Magento2 extends Configuration
{
    /**
     * @param string[] $locales
     */
    public function __construct(array $locales)
    {
        parent::__construct();

        $this->initializeDefaultConfiguration($locales);
    }

    /**
     * Initialize defaults
     *
     * @param string[] $locales
     */
    private function initializeDefaultConfiguration(array $locales): void
    {
        $this->setRecipe('magento2');
        $this->setVariable('static_content_locales', $locales);
        $this->setVariable('ENV', ['MAGE_MODE' => 'production'], 'build');

        $this->addBuildTask('deploy:vendors');
        $this->addBuildTask('magento:compile');
        $this->addBuildTask('magento:deploy:assets');

        $this->addDeployTask('magento:config:import');
        $this->addDeployTask('magento:upgrade:db');
        $this->addDeployTask('magento:cache:flush');

        $this->setSharedFiles([
            'app/etc/env.php',
            'pub/errors/local.xml',
            'pub/.user.ini',
        ]);

        $this->setSharedFolders([
            'var/log',
            'var/report',
            'var/session',
            'pub/media',
        ]);

        $this->addDeployExclude('phpserver/');
        $this->addDeployExclude('docker/');
        $this->addDeployExclude('dev/');
        $this->addDeployExclude('deploy/');
    }
}
