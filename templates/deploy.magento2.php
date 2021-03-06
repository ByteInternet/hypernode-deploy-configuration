<?php

namespace Hypernode\DeployConfiguration;

/**
 * Start by setting up the configuration
 *
 * The magento 2 configuration contains some default configuration for shared folders / files and running installers
 * @see ApplicationTemplate\Magento2::initializeDefaultConfiguration
 */
$configuration = new ApplicationTemplate\Magento2('https://github.com/ByteInternet/deploy-configuration.git', ['nl_NL'], ['en_GB', 'nl_NL']);

$productionStage = $configuration->addStage('production', 'example.com');
$productionStage->addServer('appname.hypernode.io');

return $configuration;
