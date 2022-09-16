<?php

namespace Hypernode\DeployConfiguration\PlatformConfiguration;

use Hypernode\DeployConfiguration\ServerRole;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

/**
 * Deploys nginx configuration from your repository to the server
 */
class NginxConfiguration implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;

    use StageConfigurableTrait;

    /**
     * @var string
     */
    private $sourceFolder;

    /**
     * @param string $sourceFolder Location of nginx source files in your repository
     */
    public function __construct(string $sourceFolder = 'etc/nginx/')
    {
        $this->sourceFolder = $sourceFolder;
        $this->setServerRoles([ServerRole::APPLICATION, ServerRole::LOAD_BALANCER]);
    }

    public function getSourceFolder(): string
    {
        return $this->sourceFolder;
    }
}
