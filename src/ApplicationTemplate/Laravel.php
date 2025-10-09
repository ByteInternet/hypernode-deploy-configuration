<?php

namespace Hypernode\DeployConfiguration\ApplicationTemplate;

use Hypernode\DeployConfiguration\Configuration;

class Laravel extends Configuration
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
        $this->setRecipe('laravel');

        $this->addDeployTask('artisan:storage:link');
        $this->addDeployTask('artisan:config:cache');
        $this->addDeployTask('artisan:route:cache');
        $this->addDeployTask('artisan:view:cache');
        $this->addDeployTask('artisan:event:cache');
        $this->addDeployTask('artisan:migrate');
    }
}
