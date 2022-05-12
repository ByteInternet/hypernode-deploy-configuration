<?php

namespace Hypernode\DeployConfiguration;

class ServerRole
{
    /**
     * Available server roles
     */
    public const APPLICATION = 'application';
    public const APPLICATION_FIRST = 'application_first';
    public const LOAD_BALANCER = 'load_balancer';
    public const REDIS = 'redis';
    public const VARNISH = 'varnish';
    public const DATABASE = 'database';

    /**
     * @return string[]
     */
    public static function getValues(): array
    {
        return [
            self::APPLICATION,
            self::APPLICATION_FIRST,
            self::LOAD_BALANCER,
            self::REDIS,
            self::VARNISH,
            self::DATABASE,
        ];
    }
}
