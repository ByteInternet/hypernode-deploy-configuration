<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2020
 */

namespace HipexDeployConfiguration\PlatformConfiguration;

use HipexDeployConfiguration\ServerRole;
use HipexDeployConfiguration\ServerRoleConfigurableInterface;
use HipexDeployConfiguration\ServerRoleConfigurableTrait;
use HipexDeployConfiguration\StageConfigurableInterface;
use HipexDeployConfiguration\StageConfigurableTrait;
use HipexDeployConfiguration\TaskConfigurationInterface;

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
    public function __construct($sourceFolder = 'etc/nginx/')
    {
        $this->sourceFolder = $sourceFolder;
        $this->setServerRoles([ServerRole::APPLICATION, ServerRole::LOAD_BALANCER]);
    }

    /**
     * @return string
     */
    public function getSourceFolder()
    {
        return $this->sourceFolder;
    }
}
