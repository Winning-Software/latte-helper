<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper\Tests;

use CloudBase\LatteHelper\Controller\AbstractLatteController;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractLatteController
{
    protected string $templateDir = '/tests/templates';

    public function __construct(?ContainerInterface $container = null)
    {
        if ($container !== null) {
            $this->container = $container;
        }
    }

    public function index(): Response
    {
        return $this->renderTemplate('index');
    }
}
