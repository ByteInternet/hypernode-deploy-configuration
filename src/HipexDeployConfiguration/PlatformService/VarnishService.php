<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2020
 */

namespace HipexDeployConfiguration\PlatformService;

use HipexDeployConfiguration\ServerRole;
use HipexDeployConfiguration\ServerRoleConfigurableInterface;
use HipexDeployConfiguration\ServerRoleConfigurableTrait;
use HipexDeployConfiguration\StageConfigurableInterface;
use HipexDeployConfiguration\StageConfigurableTrait;
use HipexDeployConfiguration\TaskConfigurationInterface;

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

    /**
     * @return string
     */
    public function getConfigFile(): string
    {
        return $this->configFile;
    }

    /**
     * @return int
     */
    public function getFrontendPort(): int
    {
        return $this->frontendPort;
    }

    /**
     * @return int
     */
    public function getBackendPort(): int
    {
        return $this->backendPort;
    }

    /**
     * @return string
     */
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
