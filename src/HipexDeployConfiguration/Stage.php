<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration;

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

    /**
     * Stage constructor.
     *
     * @param string $name
     * @param string $domain
     * @param string $username
     */
    public function __construct(string $name, string $domain, string $username)
    {
        $this->domain = $domain;
        $this->username = $username;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string     $hostname
     * @param array|null $roles
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
