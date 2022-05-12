<?php

declare(strict_types=1);

namespace Hypernode\DeployConfiguration;

class SharedFile
{
    /**
     * @var string
     */
    private $file;

    /**
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->file;
    }
}
