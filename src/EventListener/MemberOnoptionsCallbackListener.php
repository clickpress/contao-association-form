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
        dump($dc);
        $group = [
            'akt' => 'Vollmitglied (EUR 5000,00)',
            'jug' => 'Jugendmitglied (EUR 15,00)',
            'fam' => 'Familien (EUR 45,00)',
            'foerder' => 'Förderndes Mitglied (mind. EUR 100,00)',
        ];

        return $group;
    }
}
