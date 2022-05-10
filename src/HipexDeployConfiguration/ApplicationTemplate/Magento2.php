<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. ${year}
 */

namespace HipexDeployConfiguration\ApplicationTemplate;

use HipexDeployConfiguration\ClusterSharedFolder;
use HipexDeployConfiguration\Configuration;
use HipexDeployConfiguration\Command;
use HipexDeployConfiguration\SharedFolder;

class Magento2 extends Configuration
{
    /**
     * Magento2 constructor.
     *
     * @param string $gitRepository
     * @param array|null $localesFrontend
     * @param array|null $localesBackend
     */
    public function __construct(string $gitRepository, array $localesFrontend, array $localesBackend)
    {
        parent::__construct($gitRepository);

        $this->initializeDefaultConfiguration($localesFrontend, $localesBackend);
    }

    /**
     * Initialize defaults
     *
     * @param array|null $localesFrontend
     * @param array|null $localesBackend
     */
    private function initializeDefaultConfiguration(array $localesFrontend = null, array $localesBackend = null): void
    {
        $this->setPhpVersion('php71');
        $this->addBuildCommand(new Command\Build\Composer());
        $this->addBuildCommand(new Command\Build\Magento2\SetupDiCompile());
        $this->addBuildCommand(new Command\Build\Magento2\SetupStaticContentDeploy($localesFrontend, 'frontend'));
        $this->addBuildCommand(new Command\Build\Magento2\SetupStaticContentDeploy($localesBackend, 'adminhtml'));

        $this->addDeployCommand(new Command\Deploy\Magento2\MaintenanceMode());
        $this->addDeployCommand(new Command\Deploy\Magento2\SetupUpgrade());
        $this->addDeployCommand(new Command\Deploy\Magento2\CacheFlush());

        $this->setSharedFiles([
            'app/etc/env.php',
            'pub/errors/local.xml',
        ]);

        $this->setSharedFolders([
            new SharedFolder('var/log'),
            new SharedFolder('var/report'),
            new ClusterSharedFolder('var/session'),
            new ClusterSharedFolder('pub/media'),
        ]);

        $this->addDeployExclude('phpserver/');
        $this->addDeployExclude('docker/');
        $this->addDeployExclude('dev/');
        $this->addDeployExclude('deploy/');
    }
}
