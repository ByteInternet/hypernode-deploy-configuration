<?php

namespace Hypernode\DeployConfiguration\PlatformConfiguration\Magento2;

use Hypernode\DeployConfiguration\ServerRole;
use Hypernode\DeployConfiguration\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\StageConfigurableInterface;
use Hypernode\DeployConfiguration\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

class JobQueueConsumer implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;

    use StageConfigurableTrait;

    /**
     * @var string
     */
    private $consumer;

    /**
     * @var int
     */
    private $maxMessages;

    /**
     * @var int
     */
    private $workers;

    /**
     * Create jobqueue consumer managed by supervisor
     */
    public function __construct(string $consumer, int $workers = 1, int $maxMessages = 100)
    {
        $this->consumer = $consumer;
        $this->maxMessages = $maxMessages;
        $this->workers = $workers;

        $this->setServerRoles([ServerRole::APPLICATION_FIRST]);
    }

    public function getConsumer(): string
    {
        return $this->consumer;
    }

    public function getMaxMessages(): int
    {
        return $this->maxMessages;
    }

    public function getWorkers(): int
    {
        return $this->workers;
    }
}
