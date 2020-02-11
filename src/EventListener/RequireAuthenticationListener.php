<?php

declare(strict_types=1);

/*
 * Root Protection Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2020, terminal42 gmbh
 * @author     terminal42 <https://terminal42.ch>
 * @license    MIT
 * @link       http://github.com/terminal42/contao-root-protection
 */

namespace Terminal42\RootProtectionBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\PageModel;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class RequireAuthenticationListener
{
    private $framework;

    private $tokenStorage;

    private $trustResolver;

    public function __construct(
        ContaoFramework $framework,
        TokenStorageInterface $tokenStorage,
        AuthenticationTrustResolverInterface $trustResolver
    ) {
        $this->framework     = $framework;
        $this->tokenStorage  = $tokenStorage;
        $this->trustResolver = $trustResolver;
    }

    public function onGetPageLayout(PageModel $page): void
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token || false === $this->trustResolver->isAnonymous($token)) {
            return;
        }

        $this->framework->initialize();

        /** @var PageModel $adapter */
        $adapter  = $this->framework->getAdapter(PageModel::class);
        $rootPage = $adapter->findByPk($page->rootId);

        if (null === $rootPage || !$rootPage->rootProtection) {
            return;
        }

        $username = (string) $rootPage->rootProtectionUsername;
        $password = (string) $rootPage->rootProtectionPassword;

        if (!$this->isAuthenticated($username, $password)) {
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            exit;
        }
    }

    private function isAuthenticated(string $username, string $password): bool
    {
        return !empty($_SERVER['PHP_AUTH_USER'])
               && $username === $_SERVER['PHP_AUTH_USER']
               && $password === $_SERVER['PHP_AUTH_PW'];
    }
}
