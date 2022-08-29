# Hypernode Deploy Configuration
These documentation is the guide to painlessly setup an automated deploy on the [Hypernode](https://www.hypernode.com/) platform.
The repository contains:

- Configuration objects
- Documentation

## Whats inside?
- Deployer configuration hosts / tasks
- Hypernode server setup
- Email / New Relic notification
- Cloudflare flush

## Configuration
1. Composer `require hypernode/deploy-configuration --dev` package. Only needed when you want to have autocomplete in your `deploy.php`
file.
2. Copy the deploy templates inside the root of your project as `deploy.php`. You can find the template in
[templates/deploy.php](./templates/deploy.php).
As you can see a `$configuration` variable is assigned a instance of a `Configuration` class.
This configuration object contains the whole deploy configuration and can be altered to your needs using getters/setters.
Change configuration matching you use case, and refer to the documentation for other build in configurations and tasks.
3. Setup your CI server
    1. GitLab CI [templates/.gitlab-ci.yml](./templates/.gitlab-ci.yml).
    2. Bitbucket [templates/bitbucket-pipelines.yml](./templates/bitbucket-pipelines.yml).
    3. Github Actions [templates/github-actions.yml](./templates/github-actions.yml).
4. For Magento 2 your first build will fail due to missing configuration. Login to the server and depending on your project file edit
the `app/etc/env.php` or `app/etc/local.xml`. You will find these files in `~/apps/<domain>/shared/`.

## Build steps

### 1. Build

Builds the application to prepare to run in a production environment.

You can define commands which needs to be executed during the build stage as follows:

``` php
$configuration->addBuildCommand(new \Hypernode\DeployConfiguration\Command\Build\Composer());
```

This command will execute a `composer install` in your project folder install all project dependencies.

All possible commands can be found in the `Hypernode\DeployConfiguration\Command\Build` namespace.
Refer to the API docs for usage and options.

This repository contains a few application templates which specifies the common tasks and their order to get the application build correctly.
See application templates for more information.

### 2. Deploy

Deploys the application which was build in the build stage to a given set of hosts.

First you need to define your environments / infrastructure.

``` php
$stageAcceptance = $configuration->addStage('acceptance', 'acceptance.mydomain.com');
$stageAcceptance->addServer('appname.hypernode.io');
```

To set extra SSH options (https://www.ssh.com/academy/ssh/config) for your server you can also provide these.
For example:

``` php
$stage->addServer(
    'appname.hypernode.io',
    [],
    [],
    ['LogLevel' => 'verbose']
);
```

You can define commands which needs to be executed during the deploy stage as follows:

``` php
$configuration->addDeployCommand(new \Hypernode\DeployConfiguration\Command\Deploy\Magento2\CacheFlush());
```

All possible commands can be found in the `Hypernode\DeployConfiguration\Command\Deploy` namespace.
Refer to the API docs for usage and options.

### 3. Provision Platform services / configurations

Optionally you can have some services and application configurations setup automatically from your git repository to the Hypernode platform

For example you could maintain your cron configuration in your GIT repository and have it automatically deployed to particular servers.

``` php
$configuration ->addPlatformConfiguration(
    (new \Hypernode\DeployConfiguration\PlatformConfiguration\CronConfiguration())
        ->setStage('production')
);
```

Or setup a varnish instance

``` php
$configuration->addPlatformService(new \Hypernode\DeployConfiguration\PlatformService\VarnishService());
```

For all possible tasks and configuration please refer to the API docs.

### 3. AfterDeploy tasks

After deploy tasks are triggered after a succesfull deployment.
For example notifications are available.

Usage:
``` php
$configuration->addAfterDeployTask(new \Hypernode\DeployConfiguration\AfterDeployTask\SlackWebhook());
```

## Application template

We provide a few application template which define the common set of tasks to be executed for a specific application type.
You could use those so you don't have to specify each task manually.

Available templates:
- Magento 1
- Magento 2
- Shopware 6

Example usage:
`$configuration = new Magento2('git@git.foo.bar:magento-2/project.git', ['nl_NL'], ['nl_NL'])`

## Environment variables
Some specific environment variables are required to allow the deploy image access to the git repository
or to be able to send out notifications.

### Required
- `SSH_PRIVATE_KEY` Unencrypted SSH key. The key needs to have access to: main git repository, private packages
and the SSH user. Must be base64 encoded like this:

``` bash
cat ~/.ssh/deploy_key | base64
```

### Optional
- `DEPLOY_COMPOSER_AUTH` Composer auth.json contents. This file is required if you require access to specific Composer
repositories like Magento's, 3rd party vendors, or even your own private Composer package repository. If this environment
variable does not exist, no `auth.json` will be written, so it is optional.
The auth.json must be base64 encoded like this:

``` bash
cat auth.json | base64
```

## Testing
To test your build & deploy you can run your deploy locally.

First make sure you have all the required env variables setup using.

``` bash
export SSH_PRIVATE_KEY=***
export DEPLOY_COMPOSER_AUTH=***
.... etc
```

Then start your build / deployment run command from root of the project.

*repeat -e <ENV> for all env vars that are present during build*
``` bash
docker run -it -e SSH_PRIVATE_KEY -e DEPLOY_COMPOSER_AUTH -v `pwd`:/build hypernode/deploy hypernode-deploy build -vvv
```

``` bash
docker run -it -e SSH_PRIVATE_KEY -e DEPLOY_COMPOSER_AUTH -v `pwd`:/build hypernode/deploy hypernode-deploy deploy acceptance -vvv
```
