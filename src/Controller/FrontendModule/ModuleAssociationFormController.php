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
use Contao\ModuleModel;
use Contao\ModuleRegistration;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(category="user")
 */
class ModuleAssociationFormController extends ModuleRegistration
{
    public function __construct() {}
    
    public function __invoke(ModuleModel $model, string $section): Response
    {
        parent::__construct($model, $section);

        return new Response($this->generate());
    }

    protected function compile(): void
    {
        parent::compile();
        $this->Template->slabel = $GLOBALS['TL_LANG']['FMD']['register_applicant']['button'];
    }
}
