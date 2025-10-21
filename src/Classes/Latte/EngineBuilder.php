<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper\Classes\Latte;

use Latte\Engine;
use Latte\Loaders\FileLoader;

class EngineBuilder
{
    private const string EXTENSIONS_CONFIG_PATH = '/config/latte.php';

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
            $engine->addExtension(new $class(...$args));
        }

        return $engine;
    }

    private static function getProjectDirectory(): string
    {
        if (file_exists(dirname(__DIR__, 3) . '/autoload.php')) {
            return dirname(__DIR__, 4);
        }

        return dirname(__DIR__);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private static function loadConfig(): array
    {
        $config = [];
        $configPath = sprintf(
            '%s/%s',
            EngineBuilder::getProjectDirectory(),
            EngineBuilder::EXTENSIONS_CONFIG_PATH
        );

        if (file_exists($configPath)) {
            $config = require $configPath;
        }

        return is_array($config) ? $config : [];
    }
}
