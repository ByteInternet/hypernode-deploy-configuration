<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration\Command\Build;

use function Deployer\run;
use function Deployer\test;
use HipexDeployConfiguration\Command\Command;

/**
 * Runs composer install during build.
 *
 * Will use environment variable `DEPLOY_COMPOSER_AUTH` as a base64 encoded content of `auth.json`.
 *
 * Can be generated using:
 *```bash
 *cat auth.json | base64
 *```
 *
 */
class Composer extends Command
{
    /**
     * Default installation arguments
     */
    public const DEFAULT_INSTALL_ARGUMENTS = [
        '--verbose',
        '--no-progress',
        '--no-interaction',
        '--optimize-autoloader',
        '--no-dev',
        '--ignore-platform-reqs',
    ];

    /**
     * @var string[]
     */
    private $installArguments;

    /**
     * Composer constructor.
     *
     * @param string[] $installArguments Arguments passed to `install` command of composer
     */
    public function __construct(array $installArguments = self::DEFAULT_INSTALL_ARGUMENTS)
    {
        parent::__construct(function() {
            if (!test('[ -f vendor/autoload.php ]')) {
                run('{{bin/composer}} install {{composer/install_arguments}}');
            }
        });

        $this->installArguments = $installArguments;
    }

    /**
     * @return string[]
     */
    public function getInstallArguments(): array
    {
        return $this->installArguments;
    }
}
