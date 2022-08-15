<?php

namespace Hypernode\DeployConfiguration;

/**
 * Start by setting up the configuration
 *
 * The magento 1 configuration contains some default configuration for shared folders / files and running installers
 * @see ApplicationTemplate\Magento1::initializeDefaultConfiguration
 */
$configuration = new ApplicationTemplate\Magento1();

$productionStage = $configuration->addStage('production', 'example.com');
$productionStage->addServer('appname.hypernode.io');

return $configuration;
