<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration\Command;

use HipexDeployConfiguration\StageConfigurableInterface;
use HipexDeployConfiguration\StageConfigurableTrait;
use HipexDeployConfiguration\TaskConfigurationInterface;

class Command implements TaskConfigurationInterface, StageConfigurableInterface
{
    use StageConfigurableTrait;

    /**
     * Command to execute.
     *
     * @var string|callable
     */
    private $command;

    /**
     *
     * @var string
     */
    private $workingDirectory;

    /**
     * DeployCommand constructor.
     *
     * @param string|callable $command
     */
    public function __construct($command = null)
    {
        $this->command = $command;
    }

    /**
     * @return string|callable
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return string
     */
    public function getWorkingDirectory(): ?string
    {
        return $this->workingDirectory;
    }

    /**
     * @param string $workingDirectory
     */
    public function setWorkingDirectory(string $workingDirectory): void
    {
        $this->workingDirectory = $workingDirectory;
    }
}
