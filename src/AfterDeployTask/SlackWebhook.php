<?php

namespace Hypernode\DeployConfiguration\AfterDeployTask;

use Hypernode\DeployConfiguration\Exception\EnvironmentVariableNotDefinedException;
use function Hypernode\DeployConfiguration\getenv;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

class SlackWebhook implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;

    use StageConfigurableTrait;

    /**
     * @var string
     */
    private $webHook;

    /**
     * NewRelic constructor.
     *
     * @param string|null $webHook Defaults to env `SLACK_WEBHOOK`
     * @throws EnvironmentVariableNotDefinedException
     */
    public function __construct(string $webHook = null)
    {
        $this->webHook = $webHook ?: getenv('SLACK_WEBHOOK');
    }

    /**
     * @return string
     */
    public function getWebHook(): string
    {
        return $this->webHook;
    }
}
