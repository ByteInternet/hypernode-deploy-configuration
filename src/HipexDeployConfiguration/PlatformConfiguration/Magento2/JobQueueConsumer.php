<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration\PlatformConfiguration\Magento2;

use HipexDeployConfiguration\ServerRole;
use HipexDeployConfiguration\ServerRoleConfigurableInterface;
use HipexDeployConfiguration\ServerRoleConfigurableTrait;
use HipexDeployConfiguration\StageConfigurableInterface;
use HipexDeployConfiguration\StageConfigurableTrait;
use HipexDeployConfiguration\TaskConfigurationInterface;

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
     *
     * @param string $consumer
     * @param int    $workers
     * @param int    $maxMessages
     */
    public function __construct(string $consumer, int $workers = 1, int $maxMessages = 100)
    {
        $this->consumer = $consumer;
        $this->maxMessages = $maxMessages;
        $this->workers = $workers;

        $this->setServerRoles([ServerRole::APPLICATION_FIRST]);
    }

    /**
     * @return string
     */
    public function getConsumer(): string
    {
        return $this->consumer;
    }

    /**
     * @return int
     */
    public function getMaxMessages(): int
    {
        return $this->maxMessages;
    }

    /**
     * @return int
     */
    public function getWorkers(): int
    {
        return $this->workers;
    }
}
