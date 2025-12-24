<?php

namespace Hypernode\DeployConfiguration;

/**
 * Contains information per deploy server.
 */
class Server
{
    public const OPTION_HN_BRANCHER = 'hn_brancher';
    public const OPTION_HN_BRANCHER_LABELS = 'hn_brancher_labels';
    public const OPTION_HN_BRANCHER_SETTINGS = 'hn_brancher_settings';
    public const OPTION_HN_BRANCHER_TIMEOUT = 'hn_brancher_timeout';
    public const OPTION_HN_BRANCHER_REACHABILITY_CHECK_COUNT = 'hn_brancher_reachability_check_count';
    public const OPTION_HN_BRANCHER_REACHABILITY_CHECK_INTERVAL = 'hn_brancher_reachability_check_interval';
    public const OPTION_HN_PARENT_APP = 'hn_parent_app';

    /**
     * @var string
     */
    private $hostname;

    /**
     * @var string[]
     */
    private $roles;

    private array $options;

    /**
     * @var string[]
     */
    private $sshOptions = [];

    /**
     * @param string[] $roles
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
     * @param string $option
     * @param mixed $value
     * @return void
     */
    protected function setOption(string $option, $value)
    {
        $this->options[$option] = $value;
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
