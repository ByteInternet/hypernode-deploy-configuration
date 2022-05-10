#!/usr/bin/env php
<?php

function bridging_autoloader($class)
{
    $segments = explode('\\', $class);
    if ($segments[0] === 'HipexDeployConfiguration') {
        array_shift($segments);
        $alias = 'Hypernode\\DeployConfiguration\\' . implode('\\', $segments);
        if (class_exists($alias) || interface_exists($alias) || trait_exists($alias)) {
            class_alias($alias, $class);
        }
    }
}

spl_autoload_register('bridging_autoloader', true, true);
