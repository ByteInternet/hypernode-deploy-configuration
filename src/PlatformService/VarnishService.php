<?php

namespace Hypernode\DeployConfiguration\PlatformService;

use Hypernode\DeployConfiguration\ServerRole;
use Hypernode\DeployConfiguration\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\StageConfigurableInterface;
use Hypernode\DeployConfiguration\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

class VarnishService implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;
    use StageConfigurableTrait;

    /**
     * Defaults
     */
    const DEFAULT_FRONTEND_PORT = 6181;
    const DEFAULT_BACKEND_PORT = 6182;
    const DEFAULT_MEMORY = '1024m';
    const DEFAULT_CONFIG_FILE = 'etc/varnish.vcl';

    /**
     * @var string
     */
    private $memory;

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
     * @var array
     */
    private $arguments;

    /**
     * @param string $memory
     * @param int    $frontendPort
     * @param int    $backendPort
     * @param string $configFile
     * @param array  $arguments
     */
    public function __construct(
        $memory = self::DEFAULT_MEMORY,
        $frontendPort = self::DEFAULT_FRONTEND_PORT,
        $backendPort = self::DEFAULT_BACKEND_PORT,
        $configFile = self::DEFAULT_CONFIG_FILE,
        array $arguments = []
    ) {
        $this->memory = $memory;
        $this->frontendPort = $frontendPort;
        $this->backendPort = $backendPort;
        $this->configFile = $configFile;
        $this->arguments = $arguments;

        $this->setServerRoles([ServerRole::VARNISH]);
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

    public function getMemory(): string
    {
        return $this->memory;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
