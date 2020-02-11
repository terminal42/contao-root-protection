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

use Contao\CoreBundle\Exception\ResponseException;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class RequireAuthenticationListener
{
    private $framework;

    private $requestStack;

    public function __construct(ContaoFramework $framework, RequestStack $requestStack)
    {
        $this->framework    = $framework;
        $this->requestStack = $requestStack;
    }

    public function onGetPageLayout(PageModel $page): void
    {
        if (null === $request = $this->requestStack->getCurrentRequest()) {
            return;
        }

        $this->framework->initialize();

        /** @var PageModel $pageAdapter */
        $pageAdapter = $this->framework->getAdapter(PageModel::class);
        $rootPage    = $pageAdapter->findByPk($page->rootId);

        if (null === $rootPage || !$rootPage->rootProtection) {
            return;
        }

        $username = (string) $rootPage->rootProtectionUsername;
        $password = (string) $rootPage->rootProtectionPassword;

        if ($username === $request->getUser() && $password === $request->getPassword()) {
            // Authenticated.
            return;
        }

        $response = new Response('401 Authentication Required', Response::HTTP_UNAUTHORIZED);
        $response->headers->set('WWW-Authenticate', 'Basic realm="Access denied"');

        throw new ResponseException($response);
    }
}
