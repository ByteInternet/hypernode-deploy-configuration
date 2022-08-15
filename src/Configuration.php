<?php

namespace Hypernode\DeployConfiguration;

use Hypernode\DeployConfiguration\Logging\SimpleLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

use function Deployer\task;

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
     * Default composer options
     */
    public const DEFAULT_COMPOSER_OPTIONS = [
        '--verbose',
        '--no-progress',
        '--no-interaction',
        '--optimize-autoloader',
        '--no-dev',
    ];

    /**
     * Deploy stages / environments. Usually production and test.
     *
     * @var Stage[]
     */
    private $stages = [];

    /**
     * Shared folders between deploys. Commonly used for `media`, `var/import` folders etc.
     * @var string[]
     */
    private $sharedFolders = [];

    /**
     * Files shared between deploys. Commonly used for database configurations etc.
     *
     * @var string[]
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
     * Tasks to run prior to deploying the application to build everything. For example M2 static content deploy
     * or running your gulp build.
     *
     * @var string[]
     */
    private $buildTasks = [];

    /**
     * Tasks to run on all or specific servers to deploy.
     *
     * @var string[]
     */
    private $deployTasks = [];

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
     * @var TaskConfigurationInterface[]
     */
    private $platformConfigurations = [];

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

    /**
     * @var array
     * @psalm-var array<string, mixed>
     */
    private $deployerVariables = [];

    /**
     * @var string
     */
    private $recipe = '';

    public function __construct()
    {
        $this->logger = new SimpleLogger(LogLevel::INFO);
        $this->setDefaultComposerOptions();
    }

    public function setRecipe(string $recipe)
    {
        RecipeLoader::get()->loadRecipe($recipe);
        $this->recipe = $recipe;
    }

    public function getRecipe(): string
    {
        return $this->recipe;
    }

    private function validateTask(string $task): bool
    {
        try {
            task($task);
        } catch (\InvalidArgumentException $e) {
            return false;
        }
        return true;
    }

    public function addBuildTask(string $task): void
    {
        if ($this->validateTask($task)) {
            $this->buildTasks[] = $task;
        } else {
            throw new \RuntimeException(sprintf("Build task %s does not exist!", $task));
        }
    }

    /**
     * @return string[]
     */
    public function getBuildTasks(): array
    {
        return $this->buildTasks;
    }

    public function addDeployTask(string $task): void
    {
        if ($this->validateTask($task)) {
            $this->deployTasks[] = $task;
        } else {
            throw new \RuntimeException(sprintf("Deploy task %s does not exist!", $task));
        }
    }

    /**
     * @return string[]
     */
    public function getDeployTasks(): array
    {
        return $this->deployTasks;
    }

    /**
     * @param mixed $value
     */
    public function setVariable(string $key, $value, string $stage = 'all'): void
    {
        $this->deployerVariables[$stage][$key] = $value;
    }

    /**
     * @return array
     * @psalm-return array<string, mixed>
     */
    public function getVariables(string $stage = 'all'): array
    {
        return $this->deployerVariables[$stage] ?? [];
    }

    public function addStage(string $name, string $domain, string $username = 'app'): Stage
    {
        $stage = new Stage($name, $domain, $username);
        $this->stages[] = $stage;
        return $stage;
    }

    /**
     * @param string[] $options
     * @return void
     */
    public function setComposerOptions(array $options): void
    {
        $this->setVariable('composer_options', implode(' ', $options));
    }

    public function setDefaultComposerOptions(): void
    {
        $this->setComposerOptions(self::DEFAULT_COMPOSER_OPTIONS);
    }

    /**
     * @return Stage[]
     */
    public function getStages(): array
    {
        return $this->stages;
    }

    /**
     * @param string[] $folders
     */
    public function setSharedFolders(array $folders): self
    {
        $this->sharedFolders = [];
        foreach ($folders as $folder) {
            $this->addSharedFolder($folder);
        }
        return $this;
    }

    public function addSharedFolder(string $folder): self
    {
        $this->sharedFolders[] = $folder;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getSharedFolders(): array
    {
        return $this->sharedFolders;
    }

    /**
     * @param string[] $files
     */
    public function setSharedFiles(array $files): self
    {
        $this->sharedFiles = [];
        foreach ($files as $file) {
            $this->addSharedFile($file);
        }
        return $this;
    }

    public function addSharedFile(string $file): self
    {
        $this->sharedFiles[] = $file;
        return $this;
    }

    /**
     * @return string[]
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
     */
    public function setDeployExclude(array $excludes): self
    {
        $this->deployExclude = [];
        foreach ($excludes as $exclude) {
            $this->addDeployExclude($exclude);
        }
        return $this;
    }

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
     * @return TaskConfigurationInterface[]
     */
    public function getAfterDeployTasks(): array
    {
        return $this->afterDeployTasks;
    }

    /**
     * @param TaskConfigurationInterface[] $afterDeployTasks
     */
    public function setAfterDeployTasks($afterDeployTasks): self
    {
        $this->afterDeployTasks = [];
        foreach ($afterDeployTasks as $taskConfig) {
            $this->addAfterDeployTask($taskConfig);
        }
        return $this;
    }

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
     * @return Configuration
     */
    public function addPlatformConfiguration(TaskConfigurationInterface $platformConfiguration): self
    {
        $this->platformConfigurations[] = $platformConfiguration;
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

    public function getPostInitializeCallbacks(): array
    {
        return $this->postInitializeCallbacks;
    }

    public function setPostInitializeCallbacks(array $callbacks): void
    {
        $this->postInitializeCallbacks = $callbacks;
    }

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
