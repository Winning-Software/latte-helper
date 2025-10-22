<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper\Classes\Latte;

use Composer\Autoload\ClassLoader;
use Latte\Engine;
use Latte\Extension;
use Latte\Loaders\FileLoader;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class EngineBuilder
{
    private const string EXTENSIONS_CONFIG_PATH = '/config/latte.php';
    private const int VENDOR_AUTOLOAD_DEPTH = 5;
    private const int VENDOR_PROJECT_ROOT_DEPTH = 6;
    private const int STANDALONE_PROJECT_ROOT_DEPTH = 3;

    public static function getEngine(string $templateDir): Engine
    {
        if (!str_starts_with($templateDir, '/')) {
            $templateDir = "/{$templateDir}";
        }

        $engine = new Engine();
        $fileLoader = new FileLoader(sprintf('%s%s', EngineBuilder::getProjectDirectory(), $templateDir));

        $engine->setTempDirectory(EngineBuilder::getProjectDirectory() . '/var/cache/latte');
        $engine->setLoader($fileLoader);

        foreach (EngineBuilder::loadConfig() as $class => $args) {
            $extension = new $class(...$args);

            if ($extension instanceof Extension) {
                $engine->addExtension($extension);
            }
        }

        return $engine;
    }

    public static function getProjectDirectory(): string
    {
        if (file_exists(dirname(__DIR__, EngineBuilder::VENDOR_AUTOLOAD_DEPTH) . '/autoload.php')) {
            return dirname(__DIR__, EngineBuilder::VENDOR_PROJECT_ROOT_DEPTH);
        }

        return dirname(__DIR__, EngineBuilder::STANDALONE_PROJECT_ROOT_DEPTH);
    }

    /**
     * @return array<string, array<int|string, mixed>>
     */
    private static function loadConfig(): array
    {
        $config = [];
        $configPath = sprintf(
            '%s%s',
            EngineBuilder::getProjectDirectory(),
            EngineBuilder::EXTENSIONS_CONFIG_PATH
        );

        if (file_exists($configPath)) {
            $loadedConfig = require $configPath;

            if (is_array($loadedConfig)) {
                foreach ($loadedConfig as $class => $args) {
                    if (is_string($class) && is_array($args)) {
                        $config[$class] = $args;
                    }
                }
            }
        }

        return $config;
    }
}
