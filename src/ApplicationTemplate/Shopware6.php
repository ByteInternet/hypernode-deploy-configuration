<?php

namespace Hypernode\DeployConfiguration\ApplicationTemplate;

use Hypernode\DeployConfiguration\Command\Build\Composer;
use Hypernode\DeployConfiguration\Command\Build\Shopware6\BuildAdministration;
use Hypernode\DeployConfiguration\Command\Build\Shopware6\BuildStorefront;
use Hypernode\DeployConfiguration\Command\Build\Shopware6\ShopwareRecovery;
use Hypernode\DeployConfiguration\Command\Deploy\Shopware6\AssetInstall;
use Hypernode\DeployConfiguration\Command\Deploy\Shopware6\CacheClear;
use Hypernode\DeployConfiguration\Command\Deploy\Shopware6\ThemeCompile;
use Hypernode\DeployConfiguration\Configuration;

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
            'var/log',
            'config/jwt',
            'public/sitemap',
            'public/media',
            'public/thumbnail',
        ]);
    }
}
