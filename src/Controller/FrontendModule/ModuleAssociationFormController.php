<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoAssociationFormBundle for Contao.
 *
 * (c) Stefan Schulz-Lauterbach
 *
 * @license LGPL-3.0-or-later
 */

namespace Clickpress\ContaoAssociationFormBundle\Controller\FrontendModule;

use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\Email;
use Contao\ModuleModel;
use Contao\ModuleRegistration;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(category="user")
 */
class ModuleAssociationFormController extends ModuleRegistration
{
    public function __construct()
    {
    }

    public function __invoke(ModuleModel $model, string $section): Response
    {
        parent::__construct($model, $section);

        return new Response($this->generate());
    }

    protected function compile(): void
    {
        $membership_options = unserialize($this->membership_options);

        $this->loadLanguageFile('tl_member');

        foreach($membership_options as $option) {
            $GLOBALS['TL_LANG']['tl_member']['membership_type'][$option['value']] = $option['label'];
        }


        $GLOBALS['TL_LANG']['tl_member']['applicant_form_privacy_accept'][1] = sprintf(
            $GLOBALS['TL_LANG']['tl_member']['applicant_form_privacy_accept'][1],
            $this->privacy_url,
            $this->notification_mail,
            $this->notification_mail
        );

        $GLOBALS['TL_LANG']['tl_member']['applicant_form_abg_accept'][1] = $this->statute_text;

        parent::compile();

        $this->Template->slabel = $GLOBALS['TL_LANG']['FMD']['register_applicant']['button'];
    }
}
