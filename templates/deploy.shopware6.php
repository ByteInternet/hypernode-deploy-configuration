<?php

namespace Hypernode\DeployConfiguration;

/**
 * Start by setting up the configuration
 *
 * The Shopware 6 configuration contains some default configuration for shared folders / files and running installers
 * @see ApplicationTemplate\Shopware6::initializeDefaultConfiguration
 */
$configuration = new ApplicationTemplate\Shopware6();

$productionStage = $configuration->addStage('production', 'example.com');
$productionStage->addServer('appname.hypernode.io');

return $configuration;
