<?php

namespace Hypernode\DeployConfiguration;

/**
 * Start by setting up the configuration
 *
 * The magento 2 configuration contains some default configuration for shared folders / files and running installers
 * @see ApplicationTemplate\Magento2::initializeDefaultConfiguration
 */
$configuration = new ApplicationTemplate\Magento2(['en_GB', 'nl_NL']);

$productionStage = $configuration->addStage('production', 'example.com');
$productionStage->addServer('appname.hypernode.io');

$testStage = $configuration->addStage('test', 'example.com');
$testStage->addBrancherServer('appname')
    ->setLabels(['stage=test', 'ci_ref=' . \getenv('GITHUB_RUN_ID') ?: 'none'])
    ->setSettings(['cron_enabled' => false, 'supervisor_enabled' => false]);

return $configuration;
