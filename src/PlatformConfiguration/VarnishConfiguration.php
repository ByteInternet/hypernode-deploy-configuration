<?php

namespace Hypernode\DeployConfiguration\PlatformConfiguration;

use Hypernode\DeployConfiguration\ServerRole;
use Hypernode\DeployConfiguration\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\StageConfigurableInterface;
use Hypernode\DeployConfiguration\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

class VarnishConfiguration implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;
    use StageConfigurableTrait;

    /**
     * Defaults
     */
    private const DEFAULT_FRONTEND_PORT = 6081;
    private const DEFAULT_BACKEND_PORT = 6082;
    private const DEFAULT_CONFIG_FILE = 'etc/varnish.vcl';
    private const DEFAULT_VARNISH_VERSION = '4.0';
    private const DEFAULT_VARNISH_MEMORY = 1024;

    /**
     * @var int
     */
    private $frontendPort;

    /**
     * @var int
     */
    private $backendPort;

    /**
     * @var string
     */
    private $configFile;

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
     * @var array
     */
    private $arguments;

    /**
     * @param int $frontendPort
     * @param int $backendPort
     * @param string $configFile
     * @param string $version
     * @param int $memory
     * @param bool $useSupervisor
     * @param array $arguments
     */
    public function __construct(
        $frontendPort = self::DEFAULT_FRONTEND_PORT,
        $backendPort = self::DEFAULT_BACKEND_PORT,
        $configFile = self::DEFAULT_CONFIG_FILE,
        $version = self::DEFAULT_VARNISH_VERSION,
        $memory = self::DEFAULT_VARNISH_MEMORY,
        $useSupervisor = false,
        array $arguments = []
    ) {
        $this->frontendPort = $frontendPort;
        $this->backendPort = $backendPort;
        $this->configFile = $configFile;
        $this->version = $version;
        $this->memory = $memory;
        $this->useSupervisor = $useSupervisor;
        $this->arguments = $arguments;

        $this->setServerRoles([ServerRole::APPLICATION, ServerRole::LOAD_BALANCER]);
    }

    public function getConfigFile(): string
    {
        return $this->configFile;
    }

    public function getFrontendPort(): int
    {
        return $this->frontendPort;
    }

    public function getBackendPort(): int
    {
        return $this->backendPort;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getMemory(): int
    {
        return $this->memory;
    }

    public function useSupervisor(): bool
    {
        return $this->useSupervisor;
    }
    
    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
