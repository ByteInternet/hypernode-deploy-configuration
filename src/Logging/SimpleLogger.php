<?php

declare(strict_types=1);

namespace Hypernode\DeployConfiguration\Logging;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Simple logger implementation using printf
 */
class SimpleLogger extends AbstractLogger
{
    private const LEVEL_MAPPING = [
        LogLevel::DEBUG => 0,
        LogLevel::INFO => 1,
        LogLevel::NOTICE => 2,
        LogLevel::WARNING => 3,
        LogLevel::ERROR => 4,
        LogLevel::CRITICAL => 5,
        LogLevel::ALERT => 6,
        LogLevel::EMERGENCY => 7
    ];

    /**
     * @var int
     */
    private $mappedLevel;

    public function __construct(string $level)
    {
        $this->mappedLevel = self::LEVEL_MAPPING[$level] ?? 1;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed   $level
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function log($level, $message, array $context = []): void
    {
        if ($this->mapLevelToNumber($level) >= $this->mappedLevel) {
            printf("%s (%s)\n", $message, json_encode($context));
        }
    }
    
    private static function mapLevelToNumber(string $level): int
    {
        return self::LEVEL_MAPPING[$level] ?? 1;
    }
}
