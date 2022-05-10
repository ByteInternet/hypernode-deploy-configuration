<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. ${year}
 */

namespace HipexDeployConfiguration;

use HipexDeployConfiguration\Command\Build\Composer;
use HipexDeployConfiguration\Command\Command;
use HipexDeployConfiguration\Command\Deploy\Magento2\CacheFlush;
use HipexDeployConfiguration\Command\Deploy\Magento2\MaintenanceMode;
use HipexDeployConfiguration\Command\Deploy\Magento2\SetupUpgrade;
use HipexDeployConfiguration\Command\Build\Magento2\SetupDiCompile;

/**
 * Start by setting up the configuration
 *
 * The magento 2 configuration contains some default configuration for shared folders / files and running installers
 * @see ApplicationTemplate\Magento2::initializeDefaultConfiguration
 */
$configuration = new ApplicationTemplate\Magento2('https://github.com/HipexBV/DeployConfiguration.git', ['nl_NL'], ['en_GB', 'nl_NL']);

$productionStage = $configuration->addStage('production', 'example.com', 'example');
$productionStage->addServer('production201.hipex.io');

return $configuration;
