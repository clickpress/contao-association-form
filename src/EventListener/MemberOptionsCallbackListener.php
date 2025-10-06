<?php

namespace Clickpress\ContaoAssociationFormBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;

#[AsCallback('tl_content', 'fields.membership.options')]
class MemberOptionsCallbackListener
{
    public function __invoke(array $attributes, DataContainer $dc): array
    {
        $group = [
            'akt' => 'Vollmitglied (EUR 45,00)',
            'red' => 'red. Beitrag (EUR 26,00)',
            'frei' => 'beitragsfrei'
        ];

        return $group;
    }
}
