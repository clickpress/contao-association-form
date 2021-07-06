<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoAssociationFormBundle for Contao.
 *
 * (c) Stefan Schulz-Lauterbach
 *
 * @license LGPL-3.0-or-later
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
    ->addLegend('member_ship_legend', 'contact_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('applicant_member_ident', 'member_ship_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('membership', 'member_ship_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('applicant_mail_sent', 'member_ship_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('applicant_exported', 'member_ship_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('membership_comments', 'member_ship_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('applicant_form_abg_accept', 'member_ship_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('applicant_form_privacy_accept', 'member_ship_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_member')
;

PaletteManipulator::create()
    ->addLegend('bank_legend', 'contact_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('iban', 'bank_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('bic', 'bank_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('bank', 'bank_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_member')
;


$GLOBALS['TL_DCA']['tl_member']['fields']['membership'] = [
    'exclude' => true,
    'filter' => true,
    'sorting' => true,
    'inputType' => 'select',
    'options' => [
        'akt' => 'Vollmitglied (EUR 45,00)',
        'red' => 'red. Beitrag (EUR 22,50)',
        'frei' => 'beitragsfrei'
    ],
    'eval' => [
        'includeBlankOption' => false,
        'chosen' => true,
        'feEditable' => true,
        'feViewable' => true,
        'feGroup' => 'membership',
        'tl_class' => 'w50',
        'doNotCopy' => true,
    ],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_member']['fields']['applicant_form_abg_accept'] = [
    'exclude' => true,
    'search' => true,
    'inputType' => 'checkbox',
    'eval' => [
        'feEditable' => true,
        'feViewable' => true,
        'feGroup' => 'membership',
        'tl_class' => 'm12',
        'mandatory' => true,
        'doNotCopy' => true,
    ],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_member']['fields']['applicant_form_privacy_accept'] = [
    'exclude' => true,
    'search' => true,
    'inputType' => 'checkbox',
    'eval' => [
        'feEditable' => true,
        'feViewable' => true,
        'feGroup' => 'membership',
        'tl_class' => 'm12',
        'mandatory' => true,
        'doNotCopy' => true,
    ],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_member']['fields']['applicant_mail_sent'] = [
    'exclude' => true,
    'search' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w50',
        'doNotCopy' => true,
    ],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_member']['fields']['applicant_exported'] = [
    'exclude' => true,
    'search' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w50',
        'doNotCopy' => true,
    ],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_member']['fields']['applicant_member_ident'] = [
    'exclude' => true,
    'search' => true,
    'inputType' => 'text',
    'eval' => [
        'tl_class' => 'w50',
        'feEditable' => true,
        'feViewable' => true,
        'doNotCopy' => true,
    ],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_member']['fields']['iban'] = [
    'exclude' => true,
    'search' => true,
    'inputType' => 'text',
    'eval' => [
        'feEditable' => true,
        'feViewable' => true,
        'feGroup' => 'konto',
        'tl_class' => 'w50',
        'rgxp' => 'iban',
        'mandatory' => true,
        'doNotCopy' => true,
    ],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_member']['fields']['bic'] = [
    'exclude' => true,
    'search' => true,
    'inputType' => 'text',
    'eval' => [
        'feEditable' => true,
        'feViewable' => true,
        'feGroup' => 'konto',
        'tl_class' => 'w50',
        'rgxp' => 'bic',
        'mandatory' => true,
        'doNotCopy' => true,
    ],
    'sql' => "varchar(255) NOT NULL default ''",
];
$GLOBALS['TL_DCA']['tl_member']['fields']['bank'] = [
    'exclude' => true,
    'search' => true,
    'inputType' => 'text',
    'eval' => [
        'feEditable' => true,
        'feViewable' => true,
        'feGroup' => 'konto',
        'tl_class' => 'w50',
        'mandatory' => true,
        'doNotCopy' => true,
    ],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_member']['fields']['membership_comments'] = [
    'exclude' => true,
    'search' => true,
    'inputType' => 'textarea',
    'eval' => [
        'feEditable' => true,
        'feViewable' => true,
        'feGroup' => 'membership',
        'style' => 'height:200px',
        'preserveTags' => true,
        'rte' => 'ace|html',
        'tl_class' => 'clr',
        'doNotCopy' => true,
    ],
    'sql' => 'text NULL',
];

// Edit fields
$GLOBALS['TL_DCA']['tl_member']['fields']['gender']['eval']['mandatory'] = true;
$GLOBALS['TL_DCA']['tl_member']['fields']['dateOfBirth']['eval']['mandatory'] = true;
$GLOBALS['TL_DCA']['tl_member']['fields']['country']['default'] = 'de';
$GLOBALS['TL_DCA']['tl_member']['fields']['street']['eval']['mandatory'] = true;
$GLOBALS['TL_DCA']['tl_member']['fields']['phone']['eval']['mandatory'] = true;
$GLOBALS['TL_DCA']['tl_member']['fields']['postal']['eval']['mandatory'] = true;
$GLOBALS['TL_DCA']['tl_member']['fields']['city']['eval']['mandatory'] = true;
$GLOBALS['TL_DCA']['tl_member']['fields']['country']['eval']['mandatory'] = true;
//$GLOBALS['TL_DCA']['tl_member']['fields']['']['eval']['mandatory'] = true;
