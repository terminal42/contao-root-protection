<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
    ->addLegend('rootProtection_legend', 'publish_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('rootProtection', 'rootProtection_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('root', 'tl_page')
    ->applyToPalette('rootfallback', 'tl_page')
;

$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'rootProtection';
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['rootProtection'] = 'rootProtectionUsername,rootProtectionPassword';

$GLOBALS['TL_DCA']['tl_page']['fields']['rootProtection'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50', 'submitOnChange' => true],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['rootProtectionUsername'] = [
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['tl_class' => 'clr w50', 'mandatory' => true],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['rootProtectionPassword'] = [
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50', 'mandatory' => true],
    'sql' => "varchar(255) NOT NULL default ''",
];
