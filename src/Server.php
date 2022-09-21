<?php

namespace Hypernode\DeployConfiguration;

/**
 * Contains information per deploy server.
 */
class Server
{
    public const OPTION_HN_EPHEMERAL = 'hn_ephemeral';
    public const OPTION_HN_PARENT_APP = 'hn_parent_app';

    /**
     * @var string
     */
    private $hostname;

    /**
     * @var string[]
     */
    private $roles;

    /**
     * @var string[]
     */
    private $options = [];

    /**
     * @var string[]
     */
    private $sshOptions = [];

    /**
     * @param string[] $roles
     * @param string[] $options
     */
    public function __construct(string $hostname, array $roles = null, array $options = [])
    {
        $this->hostname = $hostname;
        $this->roles = $roles ?: ServerRole::getValues();
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function setHostname(string $hostname): void
    {
        $this->hostname = $hostname;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return string[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return string[]
     */
    public function getSshOptions(): array
    {
        return $this->sshOptions;
    }

    /**
     * @param $options
     */
    public function setSshOptions(array $options): void
    {
        $this->sshOptions = $options;
    }

    public function addSshOption(string $name, string $value): void
    {
        $this->sshOptions[$name] = $value;
    }
}
