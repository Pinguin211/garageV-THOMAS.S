<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AutomaticInterface
{
    /** Login  */
    private string | NULL $login_force_route_redirect = NULL;

    /** CSRF Token */
    private string | null $csrf_token_key = null;
    private string | null $csrf_token_node_id = null;

    public function __construct(private AuthenticationUtils $authenticationUtils, private SessionInterface $session)
    {
    }

    public function getParams(): array
    {
        return array_merge(
            ["login" => $this->authenticationUtilsParams()],
            ["csrf" => $this->csrfTokenInfo()]
        );
    }

    /**
     * Info pour le login
     * @return array
     */
    private function authenticationUtilsParams(): array
    {
        return [
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
            'last_email' => $this->authenticationUtils->getLastUsername(),
            'force_redirect' => $this->login_force_route_redirect,
        ];
    }

    /**
     * @param string $route - Route à rediriger si form de connexion validée
     * @return void
     */
    public function setLoginForceRedirectRoute(string $route): void
    {
        $this->login_force_route_redirect = $route;
    }

    private function csrfTokenInfo(): array
    {
        return [
            'key' => $this->csrf_token_key,
            'id' => $this->csrf_token_node_id,
        ];
    }

    /**
     * @param string $node_id
     * @return void
     */
    public function setCsrfToken(string $node_id = 'csrf_token'): void
    {
        $this->session->start();
        $this->csrf_token_key = $this->session->getId();
        $this->csrf_token_node_id = $node_id;
    }
}