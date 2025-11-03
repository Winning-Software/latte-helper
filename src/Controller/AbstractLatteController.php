<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper\Controller;

use CloudBase\LatteHelper\Classes\Latte\Trait\LatteFactoryTrait;
use CloudBase\LatteHelper\Classes\LatteAwareApplication;
use CloudBase\LatteHelper\Classes\LatteAwareApplicationBuilder;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractLatteController extends AbstractController
{
    use LatteFactoryTrait;

    protected string $templateDir = '/views';
    private LatteAwareApplication $app;

    /**
     * @param string $template
     * @param array<string, mixed> $data
     *
     * @return Response
     */
    protected function renderTemplate(string $template, array $data = []): Response
    {
        try {
            $engine = $this->getEngine($this->templateDir);

            if (!str_contains($template, '.latte')) {
                $template .= '.latte';
            }

            return new Response(
                $engine->renderToString($template, array_merge($this->globalData(), $data))
            );
        } catch (ContainerExceptionInterface $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function getApp(): LatteAwareApplication
    {
        if (isset($this->app)) {
            return $this->app;
        }

        $this->app = LatteAwareApplicationBuilder::build($this->getUser(), $this->container);

        return $this->app;
    }

    /**
     * @return array<string, mixed>
     */
    protected function globalData(): array
    {
        return [
            'app' => $this->getApp(),
        ];
    }
}
