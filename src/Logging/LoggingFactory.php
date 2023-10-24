<?php

declare(strict_types=1);

namespace Hypernode\DeployConfiguration\Logging;

use Composer\InstalledVersions;
use Psr\Log\LoggerInterface;

class LoggingFactory
{
    public static function create(string $level): LoggerInterface
    {
        if (version_compare(InstalledVersions::getVersion('psr/log'), '3.0.0', '<')) {
            return new LegacyLogger($level);
        }
        return new SimpleLogger($level);
    }
}
