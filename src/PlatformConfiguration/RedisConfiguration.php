<?php


namespace Hypernode\DeployConfiguration\PlatformConfiguration;


use Hypernode\DeployConfiguration\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\StageConfigurableInterface;
use Hypernode\DeployConfiguration\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

class RedisConfiguration implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;
    use StageConfigurableTrait;

    /**
     * Defaults
     */
    private const DEFAULT_PORT = 6379;
    private const DEFAULT_PERSISTENCE_PORT = 6378;
    private const DEFAULT_BACKEND_REDIS_DB = 0;
    private const DEFAULT_VERSION = '5.0';
    private const DEFAULT_MEMORY = 1024;

    /**
     * @var int
     */
    private $port;

    /**
     * @var bool
     */
    private $persistence;

    /**
     * @var int
     */
    private $backendDb;

    /**
     * @var string
     */
    private $version;

    /**
     * @var int
     */
    private $memory;

    /**
     * @var bool
     */
    private $useSupervisor;

    /**
     * @param int $port
     * @param int $backendDb
     * @param string $version
     * @param int $memory
     * @param false $persistence
     * @param false $useSupervisor
     */
    public function __construct(
        $port = self::DEFAULT_PORT,
        $backendDb = self::DEFAULT_BACKEND_REDIS_DB,
        $version = self::DEFAULT_VERSION,
        $memory = self::DEFAULT_MEMORY,
        $persistence = false,
        $useSupervisor = false
    ) {
        $this->port = (self::DEFAULT_PORT && $persistence) ? self::DEFAULT_PERSISTENCE_PORT : $port;
        $this->backendDb = $backendDb;
        $this->version = $version;
        $this->memory = $memory;
        $this->persistence = $persistence;
        $this->useSupervisor = $useSupervisor;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return bool
     */
    public function getPersistence(): bool
    {
        return $this->persistence;
    }

    /**
     * @return int
     */
    public function getBackendDb(): int
    {
        return $this->backendDb;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return int
     */
    public function getMemory(): int
    {
        return $this->memory;
    }

    /**
     * @return bool
     */
    public function getUseSupervisor(): bool
    {
        return $this->useSupervisor;
    }
}
