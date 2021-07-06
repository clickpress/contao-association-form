<?php

namespace Clickpress\ContaoAssociationFormBundle\EventListener;

use Contao\DataContainer;

class MemberOnoptionsCallbackListener
{
    /**
     * @Callback(table="tl_member", target="fields.membership.options")
     */
    public function onOptionsCallback(DataContainer $dc): array
    {
        $group = [
            'akt' => 'Vollmitglied (EUR 5000,00)',
            'red' => 'red. Beitrag (EUR 22,50)',
            'frei' => 'beitragsfrei'
        ];

        return $group;
    }
}
