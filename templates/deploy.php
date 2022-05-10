<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. ${year}
 */
/** @noinspection PhpUnhandledExceptionInspection */

// Namespace declaration is not required but just removes the pain of having to use all the config classes separately
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
 * Obviously 'https://github.com/HipexBV/DeployConfiguration.git' is an example. This should be the url to your git repo
 */
$configuration = new Configuration('https://github.com/HipexBV/DeployConfiguration.git');

// The default is PHP 7.2 however and does not need to be set.
$configuration->setPhpVersion('php72');




/**
 * We will setup a cluster environment with a load balancer, 2 app servers and a database server.
 * If your setup only contains one server you can leave out the roles argument and just setup 1 server
 *
 * Please note that currently varnish is not automatically configured, for help configuring varnish please contact support.
 */
$productionStage = $configuration->addStage(
    'production', // Name for the deployment stage
    'example.com', // Domain name
    'example' // SSH User
);
$productionStage->addServer('production201.hipex.io', [ServerRole::LOAD_BALANCER, ServerRole::VARNISH]);
$productionStage->addServer('production202.hipex.io', [ServerRole::APPLICATION, ServerRole::APPLICATION_FIRST]);
$productionStage->addServer('production203.hipex.io', [ServerRole::APPLICATION]);
$productionStage->addServer('production204.hipex.io', [ServerRole::DATABASE, ServerRole::REDIS]);





/**
 * Next is the test environment
 *
 * With `addServer` we leave the roles argument empty so this server will fulfill all deployment roles.
 */
$testStage = $configuration->addStage('test', 'test.example.com', 'example');
$testStage->addServer('production205');




/**
 * Adding extra deploy commands allow you to add actions to your deploy.
 *
 * Here are some examples. Configuration from these examples is mostly set trough the environment variables. They
 * can also be set using the constructor arguments of the commands.
 */
$configuration->addAfterDeployCommand(new AfterDeployTask\NewRelic());
$configuration->addAfterDeployCommand(new AfterDeployTask\EmailNotification());
$configuration->addAfterDeployCommand(new AfterDeployTask\Cloudflare());
$configuration->addAfterDeployCommand(new AfterDeployTask\SlackWebhook(
    'https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX'
));




/**
 * Running a Magento 1 deploy with frontend build and setting up shared files / folders
 *
 * @see deploy.magento1.php for Magento 1 specific template with default configurations set
 */
$configuration->addBuildCommand(new Command('cd skin/frontend/package/theme && yarn install'));
$configuration->addBuildCommand(new Command('cd skin/frontend/package/theme && yarn deploy'));

$configuration->addDeployCommand(new Command\Deploy\Magento1\MagerunSetupRun());
$configuration->addDeployCommand(new Command\Deploy\Magento1\MagerunCacheFlush());

// Files shared between deploys
$configuration->setSharedFiles([
    'app/etc/local.xml',
    'errors/local.xml',
]);

// Directories shared between deploys
$configuration->setSharedFolders([
    'var',
    'media',
    'sitemap',
]);




/**
 * A simple Magento 2 build
 *
 * @see deploy.magento2.php for Magento 2 specific template with default magento 2 configurations set.
 */
$configuration->addBuildCommand(new Command\Build\Composer());
$configuration->addBuildCommand(new Command\Build\Magento2\SetupDiCompile());
$configuration->addBuildCommand(new Command\Build\Magento2\SetupStaticContentDeploy());

$configuration->addDeployCommand(new Command\Deploy\Magento2\MaintenanceMode());
$configuration->addDeployCommand(new Command\Deploy\Magento2\SetupUpgrade());
$configuration->addDeployCommand(new Command\Deploy\Magento2\CacheFlush());

/**
 * Return our configuration to the deploy image
 */
return $configuration;
