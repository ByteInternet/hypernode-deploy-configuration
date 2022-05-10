<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration;

use HipexDeployConfiguration\Exception\EnvironmentVariableNotDefinedException;

/**
 * @param string $variable
 * @return string
 * @throws EnvironmentVariableNotDefinedException
 */
function getenv(string $variable): string
{
    $value = \getenv($variable);
    if ($value === false) {
        throw new EnvironmentVariableNotDefinedException(sprintf('Environment variable %s is not defined', $variable));
    }

    return $value;
}
