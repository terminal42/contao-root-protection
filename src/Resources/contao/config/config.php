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

use Terminal42\RootProtectionBundle\EventListener\RequireAuthenticationListener;

$GLOBALS['TL_HOOKS']['getPageLayout'][] = [RequireAuthenticationListener::class, 'onGetPageLayout'];
