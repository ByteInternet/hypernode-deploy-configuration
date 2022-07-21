<?php

namespace Hypernode\DeployConfiguration;

use Hypernode\DeployConfiguration\Command\Command;
use Hypernode\DeployConfiguration\Command\DeployCommand;
use Hypernode\DeployConfiguration\Logging\SimpleLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

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
     * Configurations for after deploy tasks. Commonly used to send deploy email or push a New Relic deploy tag.
     * These after deploy tasks are run on the production server(s).
     *
     * @see \Hypernode\DeployConfiguration\AfterDeployTask\Cloudflare
     * @see \Hypernode\DeployConfiguration\AfterDeployTask\EmailNotification
     * @see \Hypernode\DeployConfiguration\AfterDeployTask\NewRelic
     * @see \Hypernode\DeployConfiguration\AfterDeployTask\SlackWebhook
     *
     * @var TaskConfigurationInterface[]
     */
    private $afterDeployTasks = [];

    /**
     * Server configurations to automatically provision from your repository to the Hypernode platform
     *
     * @var array
     * @deprecated Platform configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account
     */
    private $platformConfigurations = [];

    /**
     * Addition services to run
     *
     * @var array
     * @deprecated Platform service configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account
     */
    private $platformServices = [];

    /**
     * @var string
     */
    private $phpVersion = 'php';

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
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(string $gitRepository)
    {
        $this->gitRepository = $gitRepository;
        $this->logger = new SimpleLogger(LogLevel::INFO);
    }

    public function getGitRepository(): string
    {
        return $this->gitRepository;
    }

    public function addStage(string $name, string $domain, string $username = 'app'): Stage
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
     * @param string[] $excludes
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
     * @return $this
     */
    public function addDeployCommand(DeployCommand $command): self
    {
        $this->deployCommands[] = $command;
        return $this;
    }

    /**
     * @return TaskConfigurationInterface[]
     */
    public function getAfterDeployTasks(): array
    {
        return $this->afterDeployTasks;
    }

    /**
     * @param TaskConfigurationInterface[] $afterDeployTasks
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
     * @return $this
     */
    public function addAfterDeployTask(TaskConfigurationInterface $taskConfig): self
    {
        $this->afterDeployTasks[] = $taskConfig;
        return $this;
    }

    /**
     * @return TaskConfigurationInterface[]
     * @deprecated Platform configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account
     */
    public function getPlatformConfigurations(): array
    {
        $this->logger->warning(
            "Platform configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account"
        );

        return $this->platformConfigurations;
    }

    /**
     * @param TaskConfigurationInterface[] $platformConfigurations
     * @return $this
     * @deprecated Platform configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account
     */
    public function setPlatformConfigurations(array $platformConfigurations): self
    {
        $this->logger->warning(
            "Platform configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account"
        );

        $this->platformConfigurations = [];
        foreach ($platformConfigurations as $serverConfiguration) {
            $this->addPlatformConfiguration($serverConfiguration);
        }
        return $this;
    }

    /**
     * @return Configuration
     * @deprecated Platform configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account
     */
    public function addPlatformConfiguration(TaskConfigurationInterface $platformConfiguration): self
    {
        $this->logger->warning(
            "Platform configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account"
        );

        $this->platformConfigurations[] = $platformConfiguration;
        return $this;
    }

    /**
     * @return TaskConfigurationInterface[]
     * @deprecated Platform service configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account
     */
    public function getPlatformServices(): array
    {
        $this->logger->warning(
            "Platform service configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account"
        );

        return $this->platformServices;
    }

    /**
     * @param TaskConfigurationInterface[] $platformServices
     * @return $this
     * @deprecated Platform service configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account
     */
    public function setPlatformServices(array $platformServices): self
    {
        $this->logger->warning(
            "Platform service configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account"
        );

        $this->platformServices = [];
        foreach ($platformServices as $platformService) {
            $this->addPlatformService($platformService);
        }
        return $this;
    }

    /**
     * @return Configuration
     * @deprecated Platform service configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account
     */
    public function addPlatformService(TaskConfigurationInterface $platformService): self
    {
        $this->logger->warning(
            "Platform service configuration is not supported on the Hypernode platform at the moment and configuration will not be taken into account"
        );

        $this->platformServices[] = $platformService;
        return $this;
    }

    public function getPhpVersion(): string
    {
        return $this->phpVersion;
    }

    public function setPhpVersion(string $phpVersion): void
    {
        $this->phpVersion = $phpVersion;
    }

    public function getPublicFolder(): string
    {
        return $this->publicFolder;
    }

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

    public function getBuildArchiveFile(): string
    {
        return $this->buildArchiveFile;
    }

    public function setBuildArchiveFile(string $buildArchiveFile): void
    {
        $this->buildArchiveFile = $buildArchiveFile;
    }

    public function getLogDir(): string
    {
        return $this->logDir;
    }

    /**
     * Directory containing log files
     */
    public function setLogDir(string $logDir): void
    {
        $this->logDir = $logDir;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
