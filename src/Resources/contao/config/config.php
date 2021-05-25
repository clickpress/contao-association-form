<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoAssociationFormBundle for Contao.
 *
 * (c) Stefan Schulz-Lauterbach
 *
 * @license LGPL-3.0-or-later
 */

use Clickpress\ContaoAssociationFormBundle\Controller\FrontendModule\ModuleAssociationForm\ModuleAssociationFormController;
use Clickpress\ContaoAssociationFormBundle\EventListener\ActivateAccountListener;
use Clickpress\ContaoAssociationFormBundle\EventListener\AddCustomRegexpListener;

$GLOBALS['FE_MOD']['user']['association_form'] = ModuleAssociationFormController::class;

$GLOBALS['TL_HOOKS']['addCustomRegexp'][] = [AddCustomRegexpListener::class, 'checkIban'];
$GLOBALS['TL_HOOKS']['addCustomRegexp'][] = [AddCustomRegexpListener::class, 'checkBic'];
$GLOBALS['TL_HOOKS']['activateAccount'][] = [ActivateAccountListener::class, 'sendAdminNotification'];
$GLOBALS['TL_HOOKS']['activateAccount'][] = [ActivateAccountListener::class, 'completeUserData'];
