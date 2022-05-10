<?php

/**
 * @author Hypernode
 * @copyright Copyright (c) Hypernode
 */

declare(strict_types=1);

namespace Hypernode\DeployConfiguration;

class SharedFolder
{
    /**
     * @var string
     */
    private $folder;

    /**
     * @param string $folder
     */
    public function __construct(string $folder)
    {
        $this->folder = $folder;
    }

    /**
     * @return string
     */
    public function getFolder(): string
    {
        return $this->folder;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->folder;
    }
}
