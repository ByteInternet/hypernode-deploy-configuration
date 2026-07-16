<?php

namespace Hypernode\DeployConfiguration\AfterDeployTask;

use Hypernode\DeployConfiguration\Exception\EnvironmentVariableNotDefinedException;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

class HypernodeAnnotation implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;

    use StageConfigurableTrait;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $app;

    /**
     * @var string
     */
    private $apiToken;

    /**
     * HypernodeAnnotation constructor.
     *
     * @param string|null $description Defaults to env `SLACK_WEBHOOK`
     * @throws EnvironmentVariableNotDefinedException
     */
    public function __construct(
        string $name = null, 
        string $description = null,
        string $app = null, 
        string $apiToken = null
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->app = $app;
        $this->apiToken = $apiToken;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getApp(): ?string
    {
        return $this->app;
    }

    /**
     * @return string
     */
    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }
}
