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
     * @param string $hostname Hostname of the server
     * @param array|null $roles Roles for the server to be applied
     * @param array $options Extra host options
     * @param array $sshOptions
     * @see ServerRole
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
     * Create a temporary (ephemeral) Hypernode instance based on given Hypernode.
     * The hostname will be defined during the deployment.
     *
     * @param string $appName Name of the Hypernode to base the ephemeral server on
     * @param array|null $roles Roles for the server to be applied
     * @param array $options Extra host options for Deployer
     * @see ServerRole
     * @return Server
     */
    public function addEphemeralServer(string $appName, array $roles = null, array $options = []): Server
    {
        $ephemeralOptions = [
            Server::OPTION_HN_EPHEMERAL => true,
            Server::OPTION_HN_PARENT_APP => $appName,
        ];
        $options = array_merge($ephemeralOptions, $options);
        $server = new Server('', $roles, $options);
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
