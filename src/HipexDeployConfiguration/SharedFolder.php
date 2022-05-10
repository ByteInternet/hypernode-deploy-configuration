<?php
/**
 * @author Emico <info@emico.nl>
 * @copyright (c) Emico B.V. 2020
 */
declare(strict_types = 1);

namespace HipexDeployConfiguration;

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
