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
use Contao\PageError403;
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

        // Find a 401 page if given
        // Contao 4.4 does not have a 401 page type yet
        $obj403 = $pageAdapter->find403ByPid($rootPage->id);
        if (null !== $obj403 && !$obj403->autoforward) {
            /** @var PageError403 $errorPage */
            $errorPage = new $GLOBALS['TL_PTY']['error_403']();

            $response = $errorPage->getResponse($rootPage);
        } else {
            $response = new Response('401 Authentication Required');
        }

        $response->headers->set('WWW-Authenticate', 'Basic realm="Access denied"');

        $response->setStatusCode(Response::HTTP_UNAUTHORIZED)->send();

        exit;
    }
}
