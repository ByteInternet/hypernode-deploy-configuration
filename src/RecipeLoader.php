<?php

namespace Hypernode\DeployConfiguration;

use Composer\InstalledVersions;
use Hypernode\DeployConfiguration\Exception\RecipeNotFoundException;

class RecipeLoader
{
    private const DEFAULT_RECIPE_PATHS = [
        __DIR__ . '/../recipes'
    ];

    /**
     * @var string[]
     */
     private $recipePaths = [];

     /**
      * @var self
      */
    private static $instance;

    public function __construct()
    {
        $envRecipePaths = \getenv('DEPLOY_RECIPE_PATHS') ?: '';
        $this->recipePaths = [
            ...explode(':', $envRecipePaths),
            ...self::DEFAULT_RECIPE_PATHS,
            InstalledVersions::getInstallPath('deployer/deployer') . '/recipe',
        ];
    }

    public static function get(): self
    {
        if (self::$instance === null) {
            self::$instance = new RecipeLoader();
        }
        return self::$instance;
    }


    public function loadRecipe(string $recipe)
    {
        if (!str_ends_with($recipe, '.php')) {
            $recipe = $recipe . '.php';
        }

        foreach ($this->recipePaths as $path) {
            $recipePath = $path . '/' . $recipe;
            if (file_exists($recipePath)) {
                require_once $recipePath;
                return;
            }
        }

        throw new RecipeNotFoundException(sprintf('Failed to find and load recipe "%s".', $recipe));
    }
}
