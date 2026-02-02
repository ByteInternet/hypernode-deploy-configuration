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
        $this->setVariable('static_content_locales', implode(' ', $locales));
        $this->setVariable('env', ['MAGE_MODE' => 'production'], 'build');

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

    /**
     * Set Magento themes and optionally allow split static deployment
     *
     * @param string[]|array<string, string> $themes Array of themes as ['vendor/theme', 'vendor/theme'] 
     *                                                  or as ['vendor/theme' => 'nl_NL en_US', 'vendor/theme' => 'nl_NL en_US']
     * @param bool $allowSplitStaticDeployment
     */
    public function setMagentoThemes(array $themes, bool $allowSplitStaticDeployment = true): void
    {
        $this->setVariable('magento_themes', $themes);
        if (!array_is_list($themes) && $allowSplitStaticDeployment) {
            $this->setVariable('split_static_deployment', true);
        }
    }

    /**
     * Set Magento backend themes
     *
     * @param string[]|array<string, string> $themes Array of themes as ['vendor/theme', 'vendor/theme']
     *                                                  or as ['vendor/theme' => 'nl_NL en_US', 'vendor/theme' => 'nl_NL en_US']
     */
    public function setMagentoBackendThemes(array $themes): void
    {
        $this->setVariable('magento_themes_backend', $themes);
        $this->setVariable('split_static_deployment', true);
    }

    /**
     * Enable high-performance static content deployment using elgentos/magento2-static-deploy.
     *
     * This uses a Go-based static content deployer that is 230-380x faster than the native
     * Magento setup:static-content:deploy command. It automatically handles both Hyvä themes
     * (using fast Go deployment) and Luma themes (dispatching to bin/magento).
     *
     * Requirements:
     * - Themes must be set using setMagentoThemes() with locale mapping (e.g., ['Vendor/theme' => 'nl_NL en_US'])
     *
     * @param bool $enabled Whether to enable high-performance static deployment (default: true)
     * @param string $version Version of magento2-static-deploy to use (default: 'latest')
     * @see https://github.com/elgentos/magento2-static-deploy
     */
    public function enableHighPerformanceStaticDeploy(bool $enabled = true, string $version = 'latest'): self
    {
        $this->setVariable('high_performance_static_deploy', $enabled);
        $this->setVariable('high_performance_static_deploy_version', $version);

        return $this;
    }
}
