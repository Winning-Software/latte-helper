<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper;

use Latte\Engine;
use Latte\Loaders\FileLoader;

class EngineBuilder
{
    public static function getEngine(string $templateDir): Engine
    {
        if (!str_starts_with($templateDir, '/')) {
            $templateDir = "/{$templateDir}";
        }

        $engine = new Engine();
        $fileLoader = new FileLoader(sprintf('%s%s', EngineBuilder::getProjectDirectory(), $templateDir));

        $engine->setTempDirectory(EngineBuilder::getProjectDirectory() . '/var/cache/latte');
        $engine->setLoader($fileLoader);

        return $engine;
    }

    private static function getProjectDirectory(): string
    {
        if (file_exists(dirname(__DIR__, 3) . '/autoload.php')) {
            return dirname(__DIR__, 4);
        }

        return dirname(__DIR__);
    }
}
