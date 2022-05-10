# Hipex Deploy Configuration
These documentation is the guide to painlessly setup an automated deploy on the [Hipex B.V.](https://www.hipex.io/)
 platform.
The repository contains:

- Configuration objects
- Documentation

## Whats inside?
- Deployer configuration hosts / tasks
- Hipex server setup
- Email / New Relic notification
- CloudFlare flush

## Configuration
1. Composer `require hipex/deploy-configuration --dev` package. Only needed when you want to have autocomplete in your `deploy.php`
file.
2. Copy the deploy templates inside the root of your project as `deploy.php`. You can find the template in
[templates/deploy.php](./templates/deploy.php). 
As you can see a `$configuration` variable is assigned a instance of a `Configuration` class. 
This configuration object contains the whole deploy configuration and can be altered to your needs using getters/setters.
Change configuration matching you use case, and refer to the documentation for other build in configurations and tasks.
3. Setup your CI server
    1.  GitLab CI [templates/.gitlab-ci.yml](./templates/.gitlab-ci.yml).
    3.  Bitbucket [templates/bitbucket-pipelines.yml](./templates/bitbucket-pipelines.yml).
4. For Magento 2 your first build will fail due to missing configuration. Login to the server and depending on your project file edit
the `app/etc/env.php` or `app/etc/local.xml`. You will find these files in `~/domains/<domain>/application/shared/`.  

## Build steps

### 1. Build

Builds the application to prepare to run in a production environment.

You can define commands which needs to be executed during the build stage as follows:

`$configuration->addBuildCommand(new \HipexDeployConfiguration\Command\Build\Composer())`

This command will execute a `composer install` in your project folder install all project dependencies.
 
All possible commands can be found in the `HipexDeployConfiguration\Command\Build` namespace.
Refer to the API docs for usage and options.

This repository contains a few application templates which specifies the common tasks and their order to get the application build correctly.
See application templates for more information.

### 2. Deploy

Deploys the application which was build in the build stage to a given set of hosts.

First you need to define your environments / infrastructure.

```
$stageAcceptance = $configuration->addStage('acceptance', 'acceptance.mydomain.com', 'my_ssh_username');
$stageAcceptance->addServer('productionxxx.hipex.io', [
    ServerRole::APPLICATION_FIRST,
    ServerRole::APPLICATION,
    ServerRole::LOAD_BALANCER,
    ServerRole::VARNISH,
    ServerRole::REDIS,
]);
```

To set extra SSH options (https://www.ssh.com/academy/ssh/config) for your server you can also provide these.
For example:

```
$stage->addServer(
    'productionxxx.hipex.io',
    [ServerRole::APPLICATION],
    [],
    ['LogLevel' => 'verbose']
```

You can define commands which needs to be executed during the deploy stage as follows:

`$configuration->addDeployCommand(new \HipexDeployConfiguration\Command\Deploy\Magento2\CacheFlush())` 

All possible commands can be found in the `HipexDeployConfiguration\Command\Deploy` namespace.
Refer to the API docs for usage and options.

### 3. Provision Platform services / configurations

Optionally you can have some services and application configurations setup automatically from your git repository to the Hipex platform

For example you could maintain your cron configuration in your GIT repository and have it automatically deployed to particular servers.

```
$configuration
  ->addPlatformConfiguration(
    (new \HipexDeployConfiguration\PlatformConfiguration\CronConfiguration())
        ->setStage('production')
  )
```

Or setup a varnish instance

```
$configuration->addPlatformService(new \HipexDeployConfiguration\PlatformService\VarnishService())
```

For all possible tasks and configuration please refer to the API docs.

### 4. AfterDeploy tasks

After deploy tasks are triggered after a succesfull deployment.
For example notifications are available.

Usage:
`$configuration->addAfterDeployTask(\HipexDeployConfiguration\AfterDeployTask\SlackWebhook())`

## Application template

We provide a few application template which define the common set of tasks to be executed for a specific application type.
You could use those so you don't have to specify each task manually.

Available templates:
- Magento 1
- Magento 2

Example usage:
`$configuration = new Magento2('git@git.foo.bar:magento-2/project.git', ['nl'], ['nl'])`

## Required environment variables
Some specific environment variables are required to allow the deploy image access to the git repository
or to be able to send out notifications.

### Required
- `SSH_PRIVATE_KEY` Unencrypted SSH key. The key needs to have access to: main git repository, private packages
and the SSH user. Must be base64 encoded like this:

```bash
cat ~/.ssh/deploy_key | base64
```

## ServerRoles and Stages

Servers are defined with a given set of server roles

```
$stage = $configuration->addStage('production', 'www.mydomain.com', 'my_ssh_username');
$stage->addServer('production1.hipex.io', [
    ServerRole::APPLICATION,
]);
$stage->addServer('production2.hipex.io', [
    ServerRole::LOADBALANCER,
]);
```

Most of the configurations can be specifically set for a given serverrole and/or stage.

```
$nginxConfiguration = (new NginxConfiguration('/etc/nginx/production'))
  ->setServerRoles([ServerRole::APPLICATION])
  ->setStage('production')
$configuration->addPlatformConfiguration($nginxConfiguration)
```

This nginx configuration task will only be executed on production1, because this is the only one having server role `APPLICATION`

Most tasks are assigned logical server roles already, so no need to specify them in those cases.


## Testing
To test your build & deploy you can run your deploy locally.

First make sure you have all the required env variables setup using.

```bash
export SSH_PRIVATE_KEY=***
export DEPLOY_COMPOSER_AUTH=***
.... etc
```

Then start your build / deployment run command from root of the project.

*repeat -e <ENV> for all env vars that are present during build*
```bash
docker run -it -e SSH_PRIVATE_KEY -e DEPLOY_COMPOSER_AUTH -v `pwd`:/build hipex/deploy hipex-deploy build -vvv 
```

```bash
docker run -it -e SSH_PRIVATE_KEY -e DEPLOY_COMPOSER_AUTH -v `pwd`:/build hipex/deploy hipex-deploy deploy acceptance -vvv 
```
