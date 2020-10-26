<?php

declare(strict_types=1);

namespace Terminal42\RootProtectionBundle\EventListener;

use Contao\CoreBundle\Exception\ResponseException;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Hook("getPageLayout")
 */
final class RequireAuthenticationListener
{
    private ContaoFramework $framework;
    private RequestStack $requestStack;

    public function __construct(ContaoFramework $framework, RequestStack $requestStack)
    {
        $this->framework = $framework;
        $this->requestStack = $requestStack;
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
