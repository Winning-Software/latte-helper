<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractLatteController extends AbstractController
{
    protected string $templateDir = '/views';

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

    protected function globalData(): array
    {
        return [];
    }
}
