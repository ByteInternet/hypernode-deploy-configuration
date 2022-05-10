<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration\Command\Build\Magento2;

use HipexDeployConfiguration\Command\Command;

class SetupStaticContentDeploy extends Command
{
    /**
     * DeployModeSet constructor.
     *
     * @param array $locales
     * @param string|null $area
     * @param array $arguments
     */
    public function __construct(array $locales = null, string $area = null, array $arguments = [])
    {
        parent::__construct(sprintf(
            '{{bin/php}} bin/magento setup:static-content:deploy --force %s %s',
            $this->getArguments($area, $arguments),
            $this->getLocales($locales)
        ));
    }

    /**
     * @param array|null $locales
     * @return string
     */
    private function getLocales(array $locales = null): string
    {
        return $locales ? implode(' ', $locales) : '';
    }

    /**
     * @param string|null $area
     * @param array $arguments
     * @return string
     */
    private function getArguments(string $area = null, array $arguments = []): string
    {
        if ($area) {
            $arguments[] = '--area=' . $area;
        }

        return implode(' ', $arguments);
    }
}
