<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper\Controller;

use CloudBase\LatteHelper\Classes\Latte\EngineBuilder;
use CloudBase\LatteHelper\Classes\LatteAwareApplication;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractLatteController extends AbstractController
{
    protected string $templateDir = '/views';
    private LatteAwareApplication $app;

    public function renderTemplate(string $template, array $data = []): Response
    {
        $engine = EngineBuilder::getEngine($this->templateDir);

        if (!str_contains($template, '.latte')) {
            $template .= '.latte';
        }

        return new Response(
            $engine->renderToString($template, array_merge(
                $this->globalData(),
                $data
            )),
        );
    }

    protected function getApp(): LatteAwareApplication
    {

        if (isset($this->app)) {
            return $this->app;
        }

        $this->app = new LatteAwareApplication();
        $this->app->setUser($this->getUser());

        try {
            /**
             * @var RequestStack $requestStack
             */
            $requestStack = $this->container->get('request_stack');
            $this->app->setRequestStack($requestStack);
        } catch (ContainerExceptionInterface $e) {
        }

        return $this->app;
    }

    protected function globalData(): array
    {
        $app = new LatteAwareApplication();
        $app->setUser($this->getUser());

        try {
            /**
             * @var RequestStack $requestStack
             */
            $requestStack = $this->container->get('request_stack');
            $app->setRequestStack($requestStack);
        } catch (ContainerExceptionInterface $e) {
        }

        return [
            'app' => $app,
        ];
    }
}
