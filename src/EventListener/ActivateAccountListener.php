<?php

namespace Clickpress\ContaoAssociationFormBundle\EventListener;

use Contao\Config;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\Date;
use Contao\Email;
use Contao\Environment;
use Contao\Idna;
use Contao\MemberModel;
use Contao\Module;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class ActivateAccountListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[AsHook('activateAccount')]
    public function completeUserData(MemberModel $objMember, Module $modRegistration): void
    {
        // Set date of membership start to now
        $objMember->membership_since = time();

        // Add membership fee
        switch ($objMember->membership) {
            case 'akt':
                $fee = 45;
                break;

            case 'red':
                $fee = 22;
                break;

            case 'frei':
                $fee = 0;
                break;

            default:
                $fee = 0;
        }

        $objMember->membership_fee = $fee;
        $objMember->save();
    }

    /**
     * Send an admin notification e-mail.
     */
    #[AsHook('activateAccount')]
    public function sendAdminNotification(MemberModel $member, Module $objModule): void
    {
        if ('' === $objModule->add_notification) {
            return;
        }

        $objEmail = new Email();

        $objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
        $objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
        $objEmail->subject = sprintf(
            $GLOBALS['TL_LANG']['MSC']['adminNotificationSubject'],
            Idna::decode(Environment::get('host'))
        );

        $strData = "\n\n";

        // Add user details
        $strData .= $GLOBALS['TL_LANG']['tl_member']['firstname'][0] . ': ' . $member->firstname . "\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['lastname'][0] . ': ' . $member->lastname . "\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['dateOfBirth'][0] . ': ' . Date::parse(
                Config::get('dateFormat'),
                $member->dateOfBirth
            ) . "\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['street'][0] . ': ' . $member->street . "\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['postal'][0] . ': ' . $member->postal . "\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['city'][0] . ': ' . $member->city . "\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['email'][0] . ': ' . $member->email . "\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['phone'][0] . ': ' . $member->phone . "\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['member_ship_legend'] . ': ' . $GLOBALS['TL_LANG']['tl_member']['membership'][$member->membership] . "\n";
        $strData .= $GLOBALS['TL_LANG']['tl_member']['membership_comments'][1] . ': ' . $member->membership_comments . "\n";

        $contaoLink = Environment::get('url') . Environment::get('path') . '/contao/main.php?do=member' . "\n";
        $objEmail->text = sprintf(
                $GLOBALS['TL_LANG']['MSC']['adminNotificationText'],
                $member->id,
                $strData . "\n",
                $contaoLink
            ) . "\n";

        $mailRecipient = '' !== $objModule->notification_mail ? $objModule->notification_mail : $GLOBALS['TL_ADMIN_EMAIL'];

        $mailRecipient = explode(',', $mailRecipient);

        if (\is_array($mailRecipient)) {
            foreach ($mailRecipient as $mail) {
                try {
                    $objEmail->sendTo($mail);
                } catch (\Exception $exception) {
                    $this->logger->log(
                        LogLevel::ERROR,
                        $exception,
                        ['contao' => new ContaoContext(__FUNCTION__, self::class)]
                    );
                }
                $this->logger->log(
                    LogLevel::INFO,
                    'Admin notification sent to ' . $mail . '!',
                    ['contao' => new ContaoContext(__FUNCTION__, self::class)]
                );
            }
        } else {
            try {
                $objEmail->sendTo($mailRecipient);
            } catch (\Exception $exception) {
                $this->logger->log(
                    LogLevel::ERROR,
                    $exception,
                    ['contao' => new ContaoContext(__FUNCTION__, self::class)]
                );
            }
            $this->logger->log(
                LogLevel::INFO,
                'Admin notification sent to ' . $mailRecipient . '!',
                ['contao' => new ContaoContext(__FUNCTION__, self::class)]
            );
        }
    }
}
