<?php

namespace Hypernode\DeployConfiguration\ApplicationTemplate;

use Hypernode\DeployConfiguration\Configuration;

class Shopware6 extends Configuration
{
    public function __construct()
    {
        parent::__construct();

        $this->initializeDefaultConfiguration();
    }

    /**
     * Initialize defaults
     *
     */
    private function initializeDefaultConfiguration(): void
    {
        $this->setRecipe('shopware6');
        $this->setPublicFolder('public');

        $this->setComposerOptions([
            '--verbose',
            '--no-progress',
            '--no-interaction',
            '--optimize-autoloader',
        ]);

        $this->addBuildTask('deploy:vendors');
        $this->addBuildTask('sw:deploy:vendors_recovery');
        $this->addBuildTask('sw:touch_install_lock');

        $this->addDeployTask('sw:build');
        $this->addDeployTask('sw:database:migrate');
        $this->addDeployTask('sw:cache:clear');
        $this->addDeployTask('sw:cache:warmup');

        $this->setSharedFiles([
            '.env',
            'install.lock',
            'public/.user.ini',
        ]);

        $this->setSharedFolders([
            'config/jwt',
            'files',
            'var/log',
            'public/sitemap',
            'public/media',
            'public/thumbnail',
        ]);

        // Override the default deploy exclude, because we need the source files (scss/ts)
        // on the deploy step to compile the assets.
        $this->setDeployExclude([
            './.git',
            './.github',
            './deploy.php',
            './.gitlab-ci.yml',
            './Jenkinsfile',
            '.DS_Store',
            '.idea',
            '.gitignore',
            '.editorconfig',
        ]);
    }
}
