<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper\Tests\UnitTests\Controller;

use CloudBase\LatteHelper\Tests\TestController;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class LatteControllerTest extends TestCase
{
    private TestController $controller;

    protected function setUp(): void
    {
        $user = $this->createMock(UserInterface::class);
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')
            ->willReturnCallback(function (string $id) {
                return in_array($id, ['security.token_storage', 'request_stack'], true);
            });
        $container->method('get')
            ->willReturnCallback(function (string $id) use ($tokenStorage, $requestStack) {
                return match ($id) {
                    'security.token_storage' => $tokenStorage,
                    'request_stack' => $requestStack,
                    default => throw new \RuntimeException("Service $id not mocked"),
                };
            });

        $this->controller = new TestController($container);
    }

    public function testItRendersTemplate(): void
    {
        $response = $this->controller->index();

        $this->assertSame(200, $response->getStatusCode());
    }
}
