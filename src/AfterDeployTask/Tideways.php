<?php

namespace Hypernode\DeployConfiguration\AfterDeployTask;

use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

class Tideways implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;

    use StageConfigurableTrait;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $namePrefix;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $environment;

    /**
     * @var string|null
     */
    private $service;

    /**
     * @var int|null
     */
    private $compareAfterMinutes;

    /**
     * @param string      $apiKey             Tideways API key, find it under "Project Settings"
     * @param string|null $name               Name of the release, defaults to Deployer release name
     * @param string|null $namePrefix         String prefixed to the release name
     * @param string|null $type               Type of the event, `release` or `marker`
     * @param string|null $description        More details about the release
     * @param string|null $environment        The environment this release is performed on
     * @param string|null $service            The service this release is performed on
     * @param int|null    $compareAfterMinutes Timeframe around the event used for performance comparison (5 - 1440)
     */
    public function __construct(
        string $apiKey,
        string $name = null,
        string $namePrefix = null,
        string $type = null,
        string $description = null,
        string $environment = null,
        string $service = null,
        int $compareAfterMinutes = null
    ) {
        $this->apiKey = $apiKey;
        $this->name = $name;
        $this->namePrefix = $namePrefix;
        $this->type = $type;
        $this->description = $description;
        $this->environment = $environment;
        $this->service = $service;
        $this->compareAfterMinutes = $compareAfterMinutes;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getNamePrefix(): ?string
    {
        return $this->namePrefix;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function getCompareAfterMinutes(): ?int
    {
        return $this->compareAfterMinutes;
    }
}
