<?php

namespace Hypernode\DeployConfiguration\PlatformService;

use Hypernode\DeployConfiguration\ServerRole;
use Hypernode\DeployConfiguration\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\StageConfigurableInterface;
use Hypernode\DeployConfiguration\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

class RedisService implements TaskConfigurationInterface, ServerRoleConfigurableInterface, StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;

    use StageConfigurableTrait;

    /**
     * Defaults
     */
    const DEFAULT_MEMORY = '1024m';

    /**
     * @var string
     */
    private $maxMemory = self::DEFAULT_MEMORY;

    /**
     * @var int
     */
    private $snapshotSaveFrequency = 0;

    /**
     * @var array
     */
    private $configIncludes = [];

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string|null
     */
    private $masterServer;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var array
     */
    protected $extraSettings = [
        'hz' => '10',
        'appendfsync' => 'no',
        'aof-rewrite-incremental-fsync' => 'yes',
        'tcp-backlog' => '8096',
        'client-output-buffer-limit normal' => '0 0 0',
        'client-output-buffer-limit slave' => '0 0 0',
        'client-output-buffer-limit pubsub' => '0 0 0',
    ];

    public function __construct(string $identifier = 'backend', int $port = 6379)
    {
        $this->identifier = $identifier;
        $this->port = $port;
        $this->setServerRoles([ServerRole::REDIS]);
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getMaxMemory(): string
    {
        return $this->maxMemory;
    }

    public function setMaxMemory(string $maxMemory): void
    {
        $this->maxMemory = $maxMemory;
    }

    /**
     * @return array
     */
    public function getConfigIncludes(): array
    {
        return $this->configIncludes;
    }

    /**
     * @param array $configIncludes
     */
    public function setConfigIncludes(array $configIncludes): void
    {
        $this->configIncludes = $configIncludes;
    }

    public function getSnapshotSaveFrequency(): int
    {
        return $this->snapshotSaveFrequency;
    }

    public function setSnapshotSaveFrequency(int $snapshotSaveFrequency): void
    {
        $this->snapshotSaveFrequency = $snapshotSaveFrequency;
    }

    public function getMasterServer(): ?string
    {
        return $this->masterServer;
    }

    public function setMasterServer(?string $masterServer): void
    {
        $this->masterServer = $masterServer;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return array
     */
    public function getExtraSettings(): array
    {
        return $this->extraSettings;
    }

    /**
     * @param array $extraSettings
     */
    public function setExtraSettings(array $extraSettings): void
    {
        $this->extraSettings = $extraSettings;
    }

    public function setExtraSetting(string $setting, string $value): void
    {
        $this->extraSettings[$setting] = $value;
    }

    /**
     * @param array $extraSettings
     */
    public function addExtraSettings(array $extraSettings): void
    {
        foreach ($extraSettings as $setting => $value) {
            $this->setExtraSetting($setting, $value);
        }
    }
}
