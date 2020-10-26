<?php

declare(strict_types=1);

namespace Terminal42\RootProtectionBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Terminal42\RootProtectionBundle\Terminal42RootProtectionBundle;

final class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(Terminal42RootProtectionBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
