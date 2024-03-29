<?php

namespace Hypernode\DeployConfiguration\AfterDeployTask;

use Hypernode\DeployConfiguration\Exception\EnvironmentVariableNotDefinedException;
use function Hypernode\DeployConfiguration\getenv;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

class NewRelic implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;

    use StageConfigurableTrait;

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * NewRelic constructor.
     *
     * @param string|null $appId Defaults to env `NEWRELIC_APP_ID`
     * @param string|null $apiKey Defaults to env `NEWRELIC_API_KEY`
     * @throws EnvironmentVariableNotDefinedException
     */
    public function __construct(string $appId = null, string $apiKey = null)
    {
        $this->appId = $appId ?: getenv('NEWRELIC_APP_ID');
        $this->apiKey = $apiKey ?: getenv('NEWRELIC_API_KEY');;
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
