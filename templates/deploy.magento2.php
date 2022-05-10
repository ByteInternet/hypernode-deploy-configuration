<?php

/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. ${year}
 */

namespace Hypernode\DeployConfiguration;

use Hypernode\DeployConfiguration\Command\Build\Composer;
use Hypernode\DeployConfiguration\Command\Command;
use Hypernode\DeployConfiguration\Command\Deploy\Magento2\CacheFlush;
use Hypernode\DeployConfiguration\Command\Deploy\Magento2\MaintenanceMode;
use Hypernode\DeployConfiguration\Command\Deploy\Magento2\SetupUpgrade;
use Hypernode\DeployConfiguration\Command\Build\Magento2\SetupDiCompile;

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
