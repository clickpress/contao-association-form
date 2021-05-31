<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoAssociationFormBundle for Contao.
 *
 * (c) Stefan Schulz-Lauterbach
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['association_form'] = str_replace('reg_activate;', 'reg_activate,add_notification;', $GLOBALS['TL_DCA']['tl_module']['palettes']['registration']);
$GLOBALS['TL_DCA']['tl_module']['palettes']['association_form'] = str_replace('disableCaptcha;', 'disableCaptcha,privacy_url,statute_url;', $GLOBALS['TL_DCA']['tl_module']['palettes']['association_form']);
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'add_notification';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['add_notification'] = 'notification_mail';

//dump($GLOBALS['TL_DCA']['tl_module']['subpalettes']);

$GLOBALS['TL_DCA']['tl_module']['fields']['add_notification'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['add_notification'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'eval' => [
        'submitOnChange' => true,
        'tl_class' => 'cbx m12',
    ],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['notification_mail'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['notification_mail'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => [
        'maxlength' => '255',
        'doNotCopy' => true,
        'tl_class' => 'long',
    ],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['privacy_url'] = [
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'text',
    'eval'                    => ['mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'dcaPicker'=>true, 'addWizardClass'=>false, 'tl_class'=>'w50'],
    'sql'                     => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['statute_url'] = [
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'text',
    'eval'                    => ['mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'dcaPicker'=>true, 'addWizardClass'=>false, 'tl_class'=>'w50'],
    'sql'                     => "varchar(255) NOT NULL default ''"
];
