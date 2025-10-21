<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper\Tests\UnitTests;

use CloudBase\LatteHelper\Classes\Latte\EngineBuilder;
use PHPUnit\Framework\TestCase;

class EngineBuilderTest extends TestCase
{
    public function testGetProjectDirectory(): void
    {
        $this->assertEquals(sprintf('%s', dirname(__DIR__, 2)), EngineBuilder::getProjectDirectory());
    }
}