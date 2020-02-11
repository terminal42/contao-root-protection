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

$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'rootProtection';

$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = str_replace(
    ';{publish_legend}',
    ';{rootProtectionLegend},rootProtection;{publish_legend}',
    $GLOBALS['TL_DCA']['tl_page']['palettes']['root']
);

$GLOBALS['TL_DCA']['tl_page']['subpalettes']['rootProtection'] = 'rootProtectionUsername,rootProtectionPassword';

$GLOBALS['TL_DCA']['tl_page']['fields']['rootProtection'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['rootProtection'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => [
        'tl_class' => 'w50',
    ],
    'sql'       => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['rootProtectionUsername'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['rootProtectionUsername'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => [
        'tl_class' => 'clr w50',
    ],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['rootProtectionPassword'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['rootProtectionPassword'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => [
        'tl_class' => 'w50',
    ],
    'sql'       => "varchar(255) NOT NULL default ''",
];
