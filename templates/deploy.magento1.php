<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. ${year}
 */

namespace HipexDeployConfiguration;

use HipexDeployConfiguration\Configuration;
use HipexDeployConfiguration\Command;

/**
 * Start by setting up the configuration
 *
 * The magento 1 configuration contains some default configuration for shared folders / files and running installers
 * @see ApplicationTemplate\Magento1::initializeDefaultConfiguration
 */
$configuration = new ApplicationTemplate\Magento1('https://github.com/HipexBV/DeployConfiguration.git');

$productionStage = $configuration->addStage('production', 'example.com', 'example');
$productionStage->addServer('production201.hipex.io');

return $configuration;
