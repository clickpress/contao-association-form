<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoAssociationFormBundle for Contao.
 *
 * (c) Stefan Schulz-Lauterbach
 *
 * @license LGPL-3.0-or-later
 */

namespace Clickpress\ContaoAssociationFormBundle\EventListener;

use Contao\Config;
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Date;
use Contao\Email;
use Contao\Environment;
use Contao\Idna;
use Contao\MemberModel;
use Contao\Module;
use Psr\Log\LogLevel;

class ActivateAccountListener
{
    /**
     * Manipulate fields.
     *
     * @Hook("activateAccount")
     */
    public function completeUserData(MemberModel $objMember, Module $modRegistration): void
    {
        // Set date of membership start to now
        $objMember->membership_since = time();

        // Add membership fee
        switch ($objMember->membership) {
            case 'akt':
                $fee = 30;
                break;

            case 'jug':
                $fee = 15;
                break;

            case 'fam':
                $fee = 45;
                break;

            default:
                $fee = 0;
        }

        $objMember->membership_fee = $fee;
        $objMember->save();
    }

    /**
     * Send an admin notification e-mail.
     *
     * @Hook("activateAccount")
     */
    public function sendAdminNotification(MemberModel $objMember, Module $objModule): void
    {
        if ('' === $objModule->add_notification) {
            return;
        }

        $objEmail = new Email();

        $objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
        $objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
        $objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['adminNotificationSubject'], Idna::decode(\Environment::get('host')));

        $strData = "\n\n";

        // Add user details
        $strData .= $GLOBALS['TL_LANG']['tl_member']['firstname'][0].': '.$objMember->firstname."\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['lastname'][0].': '.$objMember->lastname."\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['dateOfBirth'][0].': '.Date::parse(Config::get('dateFormat'), $objMember->dateOfBirth)."\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['street'][0].': '.$objMember->street."\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['postal'][0].': '.$objMember->postal."\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['city'][0].': '.$objMember->city."\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['email'][0].': '.$objMember->email."\n";

        $contaoLink = Environment::get('url').Environment::get('path').'/contao/main.php?do=member'."\n";
        $objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['adminNotificationText'], $objMember->id, $strData."\n", $contaoLink)."\n";

        $mailRecipient = '' !== $objModule->notification_mail ? $objModule->notification_mail : $GLOBALS['TL_ADMIN_EMAIL'];

        $mailRecipient = explode(',', $mailRecipient);

        if (\is_array($mailRecipient)) {
            foreach ($mailRecipient as $mail) {
                $objEmail->sendTo($mail);
                $logger = static::getContainer()->get('monolog.logger.contao');
                $logger->log(
                    LogLevel::INFO,
                    'Admin notification sent to '.$mail.'!',
                    ['contao' => new ContaoContext(__FUNCTION__, self::class)]
                );
            }
        } else {
            $objEmail->sendTo($mailRecipient);
        }
    }
}
