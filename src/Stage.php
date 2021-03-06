<?php

namespace Hypernode\DeployConfiguration;

class Stage
{
    /**
     * Domain name for this stage.
     *
     * @var string
     */
    private $domain;

    /**
     * SSH User
     *
     * @var string
     */
    private $username;

    /**
     * Servers in the stage that the project will be deployed to.
     *
     * @var Server[]
     */
    private $servers = [];

    /**
     * @var string
     */
    private $name;

    public function __construct(string $name, string $domain, string $username)
    {
        $this->domain = $domain;
        $this->username = $username;
        $this->name = $name;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string     $hostname
     * @param array      $roles
     * @param array      $options Extra host options
     * @param array      $sshOptions
     * @return Server
     */
    public function addServer(
        string $hostname,
        array $roles = null,
        array $options = [],
        array $sshOptions = []
    ): Server {
        $server = new Server($hostname, $roles, $options);
        $server->setSshOptions($sshOptions);
        $this->servers[] = $server;
        return $server;
    }

    /**
     * @return Server[]
     */
    public function getServers(): array
    {
        return $this->servers;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
