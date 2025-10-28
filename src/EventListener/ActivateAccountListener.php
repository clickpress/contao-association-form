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
    public function completeUserData(MemberModel $member, Module $registration): void
    {
        // Set date of membership start to now
        $member->membership_since = time();

        // Add membership fee
        switch ($member->membership) {
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

        $member->membership_fee = $fee;
        $member->save();
    }

    /**
     * Send an admin notification e-mail.
     */
    #[AsHook('activateAccount')]
    public function sendAdminNotification(MemberModel $member, Module $module): void
    {
        if ($module->add_notification) {
            return;
        }

        $adminMail = Config::get('adminEmail');

        $objEmail = new Email();

        $objEmail->from = $adminMail;
        $objEmail->subject = sprintf(
            $GLOBALS['TL_LANG']['MSC']['adminNotificationSubject'],
            Idna::decode(Environment::get('host'))
        );

        $mailContent = "\n\n";

        // Add user details
        $mailContent .= $GLOBALS['TL_LANG']['tl_member']['firstname'][0] . ': ' . $member->firstname . "\n";
        $mailContent .= $GLOBALS['TL_LANG']['tl_member']['lastname'][0] . ': ' . $member->lastname . "\n";
        $mailContent .= $GLOBALS['TL_LANG']['tl_member']['dateOfBirth'][0] . ': ' . Date::parse(
                Config::get('dateFormat'),
                $member->dateOfBirth
            ) . "\n";
        $mailContent .= $GLOBALS['TL_LANG']['tl_member']['street'][0] . ': ' . $member->street . "\n";
        $mailContent .= $GLOBALS['TL_LANG']['tl_member']['postal'][0] . ': ' . $member->postal . "\n";
        $mailContent .= $GLOBALS['TL_LANG']['tl_member']['city'][0] . ': ' . $member->city . "\n";
        $mailContent .= $GLOBALS['TL_LANG']['tl_member']['email'][0] . ': ' . $member->email . "\n";
        $mailContent .= $GLOBALS['TL_LANG']['tl_member']['phone'][0] . ': ' . $member->phone . "\n";
        $mailContent .= $GLOBALS['TL_LANG']['tl_member']['membership_legend'] . ': ' . $GLOBALS['TL_LANG']['tl_member']['membership'][$member->membership] . "\n";
        $mailContent .= $GLOBALS['TL_LANG']['tl_member']['membership_comments'][1] . ': ' . $member->membership_comments . "\n";

        $contaoLink = Environment::get('url') . Environment::get('path') . '/contao/main.php?do=member' . "\n";
        $objEmail->text = sprintf(
                $GLOBALS['TL_LANG']['MSC']['adminNotificationText'],
                $member->id,
                $mailContent . "\n",
                $contaoLink
            ) . "\n";

        $mailRecipient = '' !== $module->notification_mail ? $module->notification_mail : $adminMail;

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
