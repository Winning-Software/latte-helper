<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper\Classes\Latte\Trait;

use CloudBase\LatteHelper\Classes\Latte\LatteEngineFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait LatteFactoryTrait
{
    private ?LatteEngineFactory $latteFactory = null;

    public function setLatteFactory(LatteEngineFactory $factory): void
    {
        $this->latteFactory = $factory;
    }

    protected function getLatteFactory(): LatteEngineFactory
    {
        if (!$this->latteFactory) {
            throw new \LogicException('LatteEngineFactory not set. Did you configure the trait properly?');
        }

        return $this->latteFactory;
    }

    protected function getEngine(string $templateDir): \Latte\Engine
    {
        return $this->getLatteFactory()->createEngine($templateDir);
    }
}
