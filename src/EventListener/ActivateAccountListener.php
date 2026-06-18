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
        if (!$module->add_notification) {
            return;
        }

        $adminMail = Config::get('adminEmail');

        $objEmail = new Email();

        $objEmail->from = $adminMail;
        $objEmail->subject = sprintf(
            $GLOBALS['TL_LANG']['MSC']['adminNotificationSubject'] ?? 'New member registration on %s',
            Idna::decode(Environment::get('host'))
        );

        $mailContent = "\n\n";

        // Add user details
        $this->addToMail($mailContent, 'firstname', $member->firstname);
        $this->addToMail($mailContent, 'lastname', $member->lastname);

        if (!empty($member->dateOfBirth)) {
            $this->addToMail(
                $mailContent,
                'dateOfBirth',
                Date::parse((string) (Config::get('dateFormat') ?: 'Y-m-d'), (int) $member->dateOfBirth)
            );
        }

        $this->addToMail($mailContent, 'street', $member->street);
        $this->addToMail($mailContent, 'postal', $member->postal);
        $this->addToMail($mailContent, 'city', $member->city);
        $this->addToMail($mailContent, 'email', $member->email);
        $this->addToMail($mailContent, 'phone', $member->phone);
        $this->addToMail($mailContent, 'membership_legend', $this->getMembershipLabel($member->membership));
        $this->addToMail($mailContent, 'membership_comments', $member->membership_comments, 1);

        $this->addToMail($mailContent, 'sepa_owner', $member->sepa_owner);
        $this->addToMail($mailContent, 'iban', $member->iban);
        $this->addToMail($mailContent, 'bic', $member->bic);
        $this->addToMail($mailContent, 'bank', $member->bank);

        $contaoLink = Environment::get('url') . Environment::get('path') . '/contao/main.php?do=member' . "\n";
        $objEmail->text = sprintf(
                $GLOBALS['TL_LANG']['MSC']['adminNotificationText']
                    ?? 'A new member (ID %s) has registered on the website.%sMore details are available in the Contao member management: %s',
                $member->id,
                $mailContent . "\n",
                $contaoLink
            ) . "\n";

        $mailRecipient = '' !== (string) $module->notification_mail ? (string) $module->notification_mail : (string) $adminMail;

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

    private function addToMail(string &$text, string $key, mixed $field, int $labelIndex = 0): void
    {
        if (null === $field || '' === (string) $field) {
            return;
        }

        $label = $GLOBALS['TL_LANG']['tl_member'][$key] ?? $key;

        if (\is_array($label)) {
            $label = $label[$labelIndex] ?? reset($label) ?: $key;
        }

        $text .= (string) $label . ': ' . (string) $field . "\n";
    }

    private function getMembershipLabel(mixed $membership): string
    {
        if (null === $membership || '' === (string) $membership) {
            return '';
        }

        $membership = (string) $membership;

        return (string) ($GLOBALS['TL_LANG']['tl_member']['membership_type'][$membership] ?? $membership);
    }
}
