<?php

declare(strict_types=1);

namespace CloudBase\LatteHelper\Classes;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class LatteAwareApplication
{
    private RequestStack $requestStack;
    private Session $session;
    private ?UserInterface $user;

    public function getRequestStack(): RequestStack
    {
        return $this->requestStack;
    }

    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
        $session = $requestStack->getSession() instanceof Session
            ? $requestStack->getSession()
            : new Session();
        $this->setSession($session);
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    /**
     * @param string $type
     *
     * @return array<string, string>
     */
    public function getFlashes(string $type): array
    {
        return $this->session->getFlashBag()->get($type);
    }

    public function getEnvironmentOption(string $envVarName): ?string
    {
        return $_ENV[strtoupper($envVarName)] ?? null;
    }
}
