<?php

namespace Deployer;

use Hypernode\DeployConfiguration\RecipeLoader;

RecipeLoader::get()->loadRecipe('common.php');

add('recipes', ['magento1']);

set('default_timeout', 3600); // Increase when tasks take longer than that.

// These files are shared among all releases.
set('shared_files', [
    'app/etc/local.xml',
    'errors/local.xml',
]);

// These directories are shared among all releases.
set('shared_dirs', [
    'media',
    'sitemap',
    'var',
]);

// These directories are made writable (the definition of "writable" requires attention).
set('writable_dirs', [
    'media',
    'sitemap',
    'var',
]);

task('magento1:maintenance_mode:enable', static function () {
    if (has('previous_release') && test('[ -f {{previous_release}}/{{public_folder}}/.maintenance.flag ]')) {
        run('touch {{public_folder}}/.maintenance.flag');
    }
});

task('magento1:sys:setup:run', static function () {
    run('magerun sys:setup:run');
});

task('magento1:cache:flush', static function () {
    run('magerun cache:flush');
});
