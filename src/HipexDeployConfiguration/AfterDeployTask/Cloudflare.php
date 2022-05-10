<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration\AfterDeployTask;

use HipexDeployConfiguration\Exception\EnvironmentVariableNotDefinedException;
use function HipexDeployConfiguration\getenv;
use HipexDeployConfiguration\ServerRoleConfigurableInterface;
use HipexDeployConfiguration\ServerRoleConfigurableTrait;
use HipexDeployConfiguration\StageConfigurableInterface;
use HipexDeployConfiguration\StageConfigurableTrait;
use HipexDeployConfiguration\TaskConfigurationInterface;

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
