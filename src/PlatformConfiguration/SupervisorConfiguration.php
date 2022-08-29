<?php

namespace Hypernode\DeployConfiguration\PlatformConfiguration;

use Hypernode\DeployConfiguration\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\StageConfigurableInterface;
use Hypernode\DeployConfiguration\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

class SupervisorConfiguration implements
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
     * @param string $sourceFolder Directory containing the supervisor configs in your repository
     */
    public function __construct(string $sourceFolder = 'etc/supervisor/')
    {
        $this->sourceFolder = $sourceFolder;
    }

    public function getSourceFolder(): string
    {
        return $this->sourceFolder;
    }
}
