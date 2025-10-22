<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper\Classes;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

class LatteAwareApplicationBuilder
{
    /**
     * @param UserInterface|null $user
     * @param ContainerInterface $container
     *
     * @throws \RuntimeException
     *
     * @return LatteAwareApplication
     */
    public static function build(?UserInterface $user, ContainerInterface $container): LatteAwareApplication
    {
        $app = new LatteAwareApplication();
        $app->setUser($user);

        try {
            /**
             * @var RequestStack $requestStack
             */
            $requestStack = $container->get('request_stack');
            $app->setRequestStack($requestStack);
        } catch (ContainerExceptionInterface|\TypeError $e) {
            throw new \RuntimeException('request_stack service not found');
        }

        return $app;
    }
}
