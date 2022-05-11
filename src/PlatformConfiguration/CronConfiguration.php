<?php

/**
 * @author Hypernode
 * @copyright Copyright (c) Hypernode
 */

namespace Hypernode\DeployConfiguration\PlatformConfiguration;

use Hypernode\DeployConfiguration\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\StageConfigurableInterface;
use Hypernode\DeployConfiguration\StageConfigurableTrait;
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
    public function __construct($sourceFile = 'etc/cron')
    {
        $this->sourceFile = $sourceFile;
    }

    /**
     * @return string
     */
    public function getSourceFile()
    {
        return $this->sourceFile;
    }
}
