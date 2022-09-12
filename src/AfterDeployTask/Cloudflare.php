<?php

namespace Hypernode\DeployConfiguration\AfterDeployTask;

use Hypernode\DeployConfiguration\Exception\EnvironmentVariableNotDefinedException;
use function Hypernode\DeployConfiguration\getenv;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

class Cloudflare implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;

    use StageConfigurableTrait;

    /**
     * @var string
     */
    private $serviceKey;

    /**
     * Cloudflare constructor.
     *
     * @param string|null $serviceKey Defaults to env `CLOUDFLARE_SERVICE_KEY`
     * @throws EnvironmentVariableNotDefinedException
     */
    public function __construct(string $serviceKey = null)
    {
        $this->serviceKey = $serviceKey ?: getenv('CLOUDFLARE_SERVICE_KEY');
    }

    /**
     * @return string
     */
    public function getServiceKey(): string
    {
        return $this->serviceKey;
    }
}
