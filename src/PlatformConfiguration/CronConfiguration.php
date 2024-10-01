<?php

namespace Hypernode\DeployConfiguration\PlatformConfiguration;

use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableTrait;
use Hypernode\DeployConfiguration\ServerRole;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

/**
 * Deploys cron configuration from your repository to the server
 *
 * `PATH` and `APPLICATION_ROOT` are automatically added to cron environment variables. So you can simplify your cronjobs
 *
 * For example:
 * ```
 * * * * * * cd $APPLICATION_ROOT && logrun mycron php bin/console cron
 * ```
 */
class CronConfiguration implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;

    use StageConfigurableTrait;

    /**
     * @var string
     */
    private $sourceFile;

    /**
     * @var string
     */
    private $pathEnvVar = '/usr/local/bin:/usr/bin';

    /**
     * @param string $sourceFile Location of cron file in your repository
     */
    public function __construct(string $sourceFile = 'etc/cron')
    {
        $this->sourceFile = $sourceFile;
        $this->setServerRoles([ServerRole::APPLICATION_FIRST]);
    }

    public function getSourceFile(): string
    {
        return $this->sourceFile;
    }
}
