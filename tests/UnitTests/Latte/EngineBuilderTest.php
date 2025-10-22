<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper\Tests\UnitTests\Latte;

use CloudBase\LatteHelper\Classes\Latte\EngineBuilder;
use PHPUnit\Framework\TestCase;

class EngineBuilderTest extends TestCase
{
    public function testGetProjectDirectory(): void
    {
        $this->assertEquals(sprintf('%s', dirname(__DIR__, 3)), EngineBuilder::getProjectDirectory());
    }
}