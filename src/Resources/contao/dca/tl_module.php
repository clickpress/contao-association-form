<?php
use Contao\CoreBundle\DataContainer\PaletteManipulator;

/*
 * This file is part of the ContaoAssociationFormBundle for Contao.
 *
 * (c) Stefan Schulz-Lauterbach
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['association_form'] = str_replace('reg_activate;', 'reg_activate,add_notification;', $GLOBALS['TL_DCA']['tl_module']['palettes']['registration']);
$GLOBALS['TL_DCA']['tl_module']['palettes']['association_form'] = str_replace('disableCaptcha;', 'disableCaptcha,privacy_url,statute_url,statute_text,membership_options;', $GLOBALS['TL_DCA']['tl_module']['palettes']['association_form']);

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'add_notification';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['add_notification'] = 'notification_mail';

$GLOBALS['TL_DCA']['tl_module']['fields']['add_notification'] = [
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'eval' => [
        'submitOnChange' => true,
        'tl_class' => 'cbx m12',
    ],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['membership_options'] = [
    'inputType' => 'optionWizard',
    'eval' => [
        'mandatory' => true,
        'allowHtml'=>true
    ],
    'sql' => "blob NULL",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['notification_mail'] = [
    'exclude' => true,
    'inputType' => 'text',
    'eval' => [
        'maxlength' => '255',
        'doNotCopy' => true,
        'feGroup' => 'info',
        'tl_class' => 'long',
    ],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['privacy_url'] = [
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'text',
    'eval'                    => [
        'mandatory'=>true,
        'rgxp'=>'url',
        'decodeEntities'=>true,
        'maxlength'=>255,
        'dcaPicker'=>true,
        'feGroup' => 'info',
        'addWizardClass'=>false,
        'tl_class'=>'w50 m12'
    ],
    'sql'                     => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['statute_url'] = [
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'text',
    'eval'                    => [
        'mandatory'=>true,
        'feGroup' => 'info',
        'rgxp'=>'url',
        'decodeEntities'=>true,
        'maxlength'=>255,
        'dcaPicker'=>true,
        'addWizardClass'=>false,
        'tl_class'=>'w50 m12'

    ],
    'sql'                     => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['statute_text'] = [
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'textarea',
    'eval'                    => [
        'mandatory'=>true,
        'rte' => 'tinyMCE',
        'feGroup' => 'info',
        'decodeEntities'=>true,
        'style'=>'height:120px',
        'tl_class'=>'clr m12'
    ],
    'sql' => [
        'type' => 'text',
        'notnull' => false,
    ],
];
