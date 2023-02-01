<?php

namespace Deployer;

use Hypernode\DeployConfiguration\RecipeLoader;

RecipeLoader::get()->loadRecipe('common.php');

add('recipes', ['shopware']);

set('default_timeout', 3600); // Increase when tasks take longer than that.

// These files are shared among all releases.
set('shared_files', [
    '.env',
    'install.lock',
    'public/.htaccess',
    'public/.user.ini',
]);

// These directories are shared among all releases.
set('shared_dirs', [
    'config/jwt',
    'files',
    'var/log',
    'public/media',
    'public/thumbnail',
    'public/sitemap',
]);

// These directories are made writable (the definition of "writable" requires attention).
// Please note that the files in `config/jwt/*` receive special attention in the `sw:writable:jwt` task.
set('writable_dirs', [
    'config/jwt',
    'custom/plugins',
    'files',
    'public/bundles',
    'public/css',
    'public/fonts',
    'public/js',
    'public/media',
    'public/sitemap',
    'public/theme',
    'public/thumbnail',
    'var',
]);

task('sw:deploy:vendors_recovery', static function () {
    run('{{bin/composer}} {{composer_action}} -d vendor/shopware/recovery {{composer_options}} 2>&1');
});

// This task remotely executes the `cache:clear` console command on the target server.
task('sw:cache:clear', static function () {
    run('cd {{release_path}} && bin/console cache:clear');
});

// This task remotely executes the cache warmup console commands on the target server, so that the first user, who
// visits the website, doesn't have to wait for the cache to be built up.
task('sw:cache:warmup', static function () {
    run('cd {{release_path}} && bin/console cache:warmup');
    run('cd {{release_path}} && bin/console http:cache:warm:up');
});

// This task remotely executes the `database:migrate` console command on the target server.
task('sw:database:migrate', static function () {
    run('cd {{release_path}} && bin/console database:migrate --all');
});

task('sw:writable:jwt', static function () {
    run('cd {{release_path}} && chmod -R 660 config/jwt/*');
});

task('sw:build', static function () {
    run('cd {{release_path}} && ./bin/build.sh');
});

task('sw:touch_install_lock', static function () {
    run('touch install.lock');
});
