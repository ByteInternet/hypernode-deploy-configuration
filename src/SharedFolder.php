<?php

declare(strict_types=1);

namespace Hypernode\DeployConfiguration;

/**
 * @deprecated This class has been deprecated, instead of new SharedFolder('/path/to/folder'), just use '/path/to/folder'.
 */
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
