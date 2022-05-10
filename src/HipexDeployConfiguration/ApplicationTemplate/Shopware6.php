<?php

namespace HipexDeployConfiguration\ApplicationTemplate;

use HipexDeployConfiguration\ClusterSharedFolder;
use HipexDeployConfiguration\Command\Build\Composer;
use HipexDeployConfiguration\Command\Build\Shopware6\BuildAdministration;
use HipexDeployConfiguration\Command\Build\Shopware6\BuildStorefront;
use HipexDeployConfiguration\Command\Build\Shopware6\ShopwareRecovery;
use HipexDeployConfiguration\Command\Deploy\Shopware6\AssetInstall;
use HipexDeployConfiguration\Command\Deploy\Shopware6\CacheClear;
use HipexDeployConfiguration\Command\Deploy\Shopware6\ThemeCompile;
use HipexDeployConfiguration\Configuration;
use HipexDeployConfiguration\SharedFolder;

class Shopware6 extends Configuration
{
    /**
     * Shopware6 constructor.
     * @param string $gitRepository
     */
    public function __construct(string $gitRepository)
    {
        parent::__construct($gitRepository);

        $this->initializeDefaultConfiguration();
    }

    /**
     * Initialize defaults
     *
     */
    private function initializeDefaultConfiguration(): void
    {
        $this->setPhpVersion('php72');


        $this->addBuildCommand(new Composer([
            '--verbose',
            '--no-progress',
            '--no-interaction',
            '--optimize-autoloader',
            '--ignore-platform-reqs',
        ]));

        $this->addBuildCommand(new ShopwareRecovery());
        $this->addBuildCommand(new BuildAdministration());
        $this->addBuildCommand(new BuildStorefront());

        $this->addDeployCommand(new AssetInstall());
        $this->addDeployCommand(new ThemeCompile());
        $this->addDeployCommand(new CacheClear());

        $this->setSharedFiles([
            '.env',
        ]);

        $this->setSharedFolders([
            new SharedFolder('var/log'),
            new ClusterSharedFolder('config/jwt'),
            new ClusterSharedFolder('public/sitemap'),
            new ClusterSharedFolder('public/media'),
            new ClusterSharedFolder('public/thumbnail'),
        ]);
    }
}
