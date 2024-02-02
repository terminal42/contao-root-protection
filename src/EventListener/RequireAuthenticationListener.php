<?php

declare(strict_types=1);

namespace Terminal42\RootProtectionBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Exception\ResponseException;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

#[AsHook('getPageLayout')]
final class RequireAuthenticationListener
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function __invoke(PageModel $page): void
    {
        if (null === $request = $this->requestStack->getCurrentRequest()) {
            return;
        }

        $this->framework->initialize();

        /** @var PageModel $pageAdapter */
        $pageAdapter = $this->framework->getAdapter(PageModel::class);
        $rootPage = $pageAdapter->findByPk($page->rootId);

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
