<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration;

use HipexDeployConfiguration\Command\Command;
use HipexDeployConfiguration\Command\DeployCommand;

class Configuration
{
    /**
     * Default deploy excluded files
     */
    private const DEFAULT_DEPLOY_EXCLUDE = [
        './.git',
        './.github',
        './deploy.php',
        './.gitlab-ci.yml',
        './Jenkinsfile',
        '.DS_Store',
        '.idea',
        '.gitignore',
        '.editorconfig',
        '*.scss',
        '*.less',
        '*.jsx',
        '*.ts',
    ];

    /**
     * Git repository of the project, this is required.
     *
     * @var string
     */
    private $gitRepository;

    /**
     * Deploy stages / environments. Usually production and test.
     *
     * @var Stage[]
     */
    private $stages = [];

    /**
     * Shared folders between deploys. Commonly used for `media`, `var/import` folders etc.
     * @var SharedFolder[]
     */
    private $sharedFolders = [];

    /**
     * Files shared between deploys. Commonly used for database configurations etc.
     *
     * @var SharedFile[]
     */
    private $sharedFiles = [];

    /**
     * Folders that should be writable but not shared between deploys. All shared folders are writable by default.
     *
     * @var string[]
     */
    private $writableFolders = [];

    /**
     *
     * Add file / directory that will not be deployed. File patterns are added as `tar --exclude=`;
     *
     * @var string[]
     */
    private $deployExclude = self::DEFAULT_DEPLOY_EXCLUDE;

    /**
     * Commands to run prior to deploying the application to build everything. For example de M2 static content deploy
     * or running your gulp build.
     *
     * @var Command[]
     */
    private $buildCommands = [];

    /**
     * Commands to run on all or specific servers to deploy.
     *
     * @var DeployCommand[]
     */
    private $deployCommands = [];

    /**
     * Commands to execute after successful deploy. Commonly used to send deploy email or push a New Relic deploy tag.
     * These commands are run on the production server(s).
     *
     * @var Command[]
     */
    private $afterDeployTasks = [];

    /**
     * Server configurations to automatically provision from your repository to the Hipex platform
     *
     * @var array
     */
    private $platformConfigurations = [];

    /**
     * Addition services to run
     *
     * @var array
     */
    private $platformServices = [];

    /**
     * @var string
     */
    private $phpVersion = 'php74';

    /**
     * @var string
     */
    private $publicFolder = 'pub';

    /**
     * @var string
     */
    private $buildArchiveFile = 'build/build.tgz';

    /**
     * Add callbacks you want to excecute after all deploy tasks are initialized
     * This allows you to reconfigure a deployer task
     *
     * @var array
     */
    private $postInitializeCallbacks = [];

    /**
     * Directory that stores log files. Is used for automatically log aggregation over different hosts / scaling
     * applications.
     *
     * @var string
     */
    private $logDir = 'var/log';

    /**
     * Docker imaged used as base to build docker image for PHP-FPM container. Used as docker `FROM` directive. When empty
     * Hipex base image will be used `registry.hipex.cloud/hipex-services/docker-image-php/<version>-fpm`. The PHP version
     * will be replaced based on `$phpVersion` configuration.
     *
     * @var string|null
     */
    private $dockerBaseImagePhp;

    /**
     * Docker imaged used as base to build docker image for nginx container. Used as docker `FROM` directive. When empty
     * Hipex base image `registry.hipex.cloud/hipex-services/docker-image-nginx` will be used.
     *
     * @var string|null
     */
    private $dockerBaseImageNginx;

    /**
     * Name of the docker image to build, excluding registry. When empty will try these env variables:
     *  - $CI_PROJECT_PATH
     *  - $BITBUCKET_REPO_SLUG
     *
     * The final image will have a /php or /nginx added
     *
     * @var string|null
     */
    private $dockerImage;

    /**
     * Registry to push build docker image to. When empty will use `$CI_REGISTRY`.
     *
     * @var string|null
     */
    private $dockerRegistry;

    /**
     * Docker registry username. When empty will `CI_REGISTRY_USER` env variables or just skip login.
     *
     * @var string|null
     */
    private $dockerRegistryUsername;

    /**
     * Docker registry username. When empty will `CI_REGISTRY_PASSWORD` env variables or just skip login.
     *
     * @var string|null
     */
    private $dockerRegistryPassword;

    /**
     * When DevOps As A Service is enabled a `.hipex-cloud.json` file is generated and uploaded to the production
     * environment. This file is required for features like Hybrid Cloud.
     *
     * @var bool
     */
    private $daasEnabled = false;

    /**
     * ServerConfiguration constructor.
     *
     * @param string $gitRepository
     */
    public function __construct(string $gitRepository)
    {
        $this->gitRepository = $gitRepository;
    }

    /**
     * @return string
     */
    public function getGitRepository(): string
    {
        return $this->gitRepository;
    }

    /**
     * @param string $name
     * @param string $domain
     * @param string $username
     * @return Stage
     */
    public function addStage(string $name, string $domain, string $username): Stage
    {
        $stage = new Stage($name, $domain, $username);
        $this->stages[] = $stage;
        return $stage;
    }

    /**
     * @return Stage[]
     */
    public function getStages(): array
    {
        return $this->stages;
    }

    /**
     * @param SharedFolder[]|string[] $folders
     * @return $this
     */
    public function setSharedFolders(array $folders): self
    {
        $this->sharedFolders = [];
        foreach ($folders as $folder) {
            $this->addSharedFolder($folder);
        }
        return $this;
    }

    /**
     * @param SharedFolder|string $folder
     * @return $this
     */
    public function addSharedFolder($folder): self
    {
        if (!$folder instanceof SharedFolder) {
            $folder = new SharedFolder($folder);
        }
        $this->sharedFolders[] = $folder;
        return $this;
    }

    /**
     * @return SharedFolder[]
     */
    public function getSharedFolders(): array
    {
        return $this->sharedFolders;
    }

    /**
     * @param SharedFile[]|string[] $files
     * @return $this
     */
    public function setSharedFiles(array $files): self
    {
        $this->sharedFiles = [];
        foreach ($files as $file) {
            $this->addSharedFile($file);
        }
        return $this;
    }

    /**
     * @param SharedFile|string $file
     * @return $this
     */
    public function addSharedFile($file): self
    {
        if (!$file instanceof SharedFile) {
            $file = new SharedFile($file);
        }
        $this->sharedFiles[] = $file;
        return $this;
    }

    /**
     * @return SharedFile[]
     */
    public function getSharedFiles(): array
    {
        return $this->sharedFiles;
    }

    /**
     * @return string[]
     */
    public function getWritableFolders(): array
    {
        return $this->writableFolders;
    }

    /**
     * @param string $folder
     * @return $this
     */
    public function addWritableFolder(string $folder): self
    {
        $this->writableFolders[] = $folder;
        return $this;
    }

    /**
     * @param string[] $writableFolders
     */
    public function setWritableFolders(array $writableFolders): void
    {
        $this->writableFolders = [];
        foreach ($writableFolders as $folder) {
            $this->addWritableFolder($folder);
        }
    }

    /**
     * @param array $excludes
     * @return $this
     */
    public function setDeployExclude(array $excludes): self
    {
        $this->deployExclude = [];
        foreach ($excludes as $exclude) {
            $this->addDeployExclude($exclude);
        }
        return $this;
    }

    /**
     * @param string $exclude
     * @return $this
     */
    public function addDeployExclude(string $exclude): self
    {
        $this->deployExclude[] = $exclude;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getDeployExclude(): array
    {
        return $this->deployExclude;
    }

    /**
     * @return Command[]
     */
    public function getBuildCommands(): array
    {
        return $this->buildCommands;
    }

    /**
     * @param Command[] $buildCommands
     * @return $this
     */
    public function setBuildCommands(array $buildCommands): self
    {
        $this->buildCommands = [];
        foreach ($buildCommands as $command) {
            $this->addBuildCommand($command);
        }
        return $this;
    }

    /**
     * @param Command $command
     * @return $this
     */
    public function addBuildCommand(Command $command): self
    {
        $this->buildCommands[] = $command;
        return $this;
    }

    /**
     * @return DeployCommand[]
     */
    public function getDeployCommands(): array
    {
        return $this->deployCommands;
    }

    /**
     * @param DeployCommand[] $deployCommands
     * @return $this
     */
    public function setDeployCommands($deployCommands): self
    {
        $this->deployCommands = [];
        foreach ($deployCommands as $command) {
            $this->addDeployCommand($command);
        }
        return $this;
    }

    /**
     * @param DeployCommand $command
     * @return $this
     */
    public function addDeployCommand(DeployCommand $command): self
    {
        $this->deployCommands[] = $command;
        return $this;
    }

    /**
     * @return Command
     */
    public function getAfterDeployTasks(): array
    {
        return $this->afterDeployTasks;
    }

    /**
     * @param Command[] $afterDeployTasks
     * @return $this
     */
    public function setAfterDeployTasks($afterDeployTasks): self
    {
        $this->afterDeployTasks = [];
        foreach ($afterDeployTasks as $taskConfig) {
            $this->addAfterDeployTask($taskConfig);
        }
        return $this;
    }

    /**
     * @param TaskConfigurationInterface $taskConfig
     * @return $this
     */
    public function addAfterDeployTask(TaskConfigurationInterface $taskConfig): self
    {
        $this->afterDeployTasks[] = $taskConfig;
        return $this;
    }

    /**
     * @return TaskConfigurationInterface[]
     */
    public function getPlatformConfigurations(): array
    {
        return $this->platformConfigurations;
    }

    /**
     * @param TaskConfigurationInterface[] $platformConfigurations
     * @return $this
     */
    public function setPlatformConfigurations(array $platformConfigurations): self
    {
        $this->platformConfigurations = [];
        foreach ($platformConfigurations as $serverConfiguration) {
            $this->addPlatformConfiguration($serverConfiguration);
        }
        return $this;
    }

    /**
     * @param TaskConfigurationInterface $platformConfiguration
     * @return Configuration
     */
    public function addPlatformConfiguration(TaskConfigurationInterface $platformConfiguration): self
    {
        $this->platformConfigurations[] = $platformConfiguration;
        return $this;
    }

    /**
     * @return TaskConfigurationInterface[]
     */
    public function getPlatformServices(): array
    {
        return $this->platformServices;
    }

    /**
     * @param TaskConfigurationInterface[] $platformServices
     * @return $this
     */
    public function setPlatformServices(array $platformServices): self
    {
        $this->platformServices = [];
        foreach ($platformServices as $platformService) {
            $this->addPlatformService($platformService);
        }
        return $this;
    }

    /**
     * @param TaskConfigurationInterface $platformService
     * @return Configuration
     */
    public function addPlatformService(TaskConfigurationInterface $platformService): self
    {
        $this->platformServices[] = $platformService;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhpVersion(): string
    {
        return $this->phpVersion;
    }

    /**
     * @param string $phpVersion
     */
    public function setPhpVersion(string $phpVersion): void
    {
        $this->phpVersion = $phpVersion;
    }

    /**
     * @return string
     */
    public function getPublicFolder(): string
    {
        return $this->publicFolder;
    }

    /**
     * @param string $publicFolder
     */
    public function setPublicFolder(string $publicFolder): void
    {
        $this->publicFolder = $publicFolder;
    }

    /**
     * @return array
     */
    public function getPostInitializeCallbacks(): array
    {
        return $this->postInitializeCallbacks;
    }

    /**
     * @param array $callbacks
     */
    public function setPostInitializeCallbacks(array $callbacks): void
    {
        $this->postInitializeCallbacks = $callbacks;
    }

    /**
     * @param callable $callback
     */
    public function addPostInitializeCallback(callable $callback)
    {
        $this->postInitializeCallbacks[] = $callback;
    }

    /**
     * @return string
     */
    public function getBuildArchiveFile(): string
    {
        return $this->buildArchiveFile;
    }

    /**
     * @param string $buildArchiveFile
     */
    public function setBuildArchiveFile(string $buildArchiveFile): void
    {
        $this->buildArchiveFile = $buildArchiveFile;
    }

    /**
     * @return string
     */
    public function getLogDir(): string
    {
        return $this->logDir;
    }

    /**
     * Directory containing log files
     *
     * @param string $logDir
     */
    public function setLogDir(string $logDir): void
    {
        $this->logDir = $logDir;
    }

    /**
     * @return string|null
     */
    public function getDockerBaseImagePhp(): ?string
    {
        return $this->dockerBaseImagePhp;
    }

    /**
     * @param string|null $dockerBaseImagePhp
     */
    public function setDockerBaseImagePhp(?string $dockerBaseImagePhp): void
    {
        $this->dockerBaseImagePhp = $dockerBaseImagePhp;
    }

    /**
     * @return string|null
     */
    public function getDockerBaseImageNginx(): ?string
    {
        return $this->dockerBaseImageNginx;
    }

    /**
     * @param string|null $dockerBaseImageNginx
     */
    public function setDockerBaseImageNginx(?string $dockerBaseImageNginx): void
    {
        $this->dockerBaseImageNginx = $dockerBaseImageNginx;
    }

    /**
     * @return string|null
     */
    public function getDockerImage(): ?string
    {
        return $this->dockerImage;
    }

    /**
     * @param string|null $dockerImage
     */
    public function setDockerImage(?string $dockerImage): void
    {
        $this->dockerImage = $dockerImage;
    }

    /**
     * @return string|null
     */
    public function getDockerRegistry(): ?string
    {
        return $this->dockerRegistry;
    }

    /**
     * @param string|null $dockerRegistry
     */
    public function setDockerRegistry(?string $dockerRegistry): void
    {
        $this->dockerRegistry = $dockerRegistry;
    }

    /**
     * @return string|null
     */
    public function getDockerRegistryUsername(): ?string
    {
        return $this->dockerRegistryUsername;
    }

    /**
     * @param string|null $dockerRegistryUsername
     */
    public function setDockerRegistryUsername(?string $dockerRegistryUsername): void
    {
        $this->dockerRegistryUsername = $dockerRegistryUsername;
    }

    /**
     * @return string|null
     */
    public function getDockerRegistryPassword(): ?string
    {
        return $this->dockerRegistryPassword;
    }

    /**
     * @param string|null $dockerRegistryPassword
     */
    public function setDockerRegistryPassword(?string $dockerRegistryPassword): void
    {
        $this->dockerRegistryPassword = $dockerRegistryPassword;
    }

    /**
     * @return bool
     */
    public function isDaasEnabled(): bool
    {
        return $this->daasEnabled;
    }

    /**
     * @param bool $daasEnabled
     */
    public function setDaasEnabled(bool $daasEnabled): void
    {
        $this->daasEnabled = $daasEnabled;
    }
}
