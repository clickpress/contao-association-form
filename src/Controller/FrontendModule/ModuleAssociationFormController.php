<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoAssociationFormBundle for Contao.
 *
 * (c) Stefan Schulz-Lauterbach
 *
 * @license LGPL-3.0-or-later
 */

namespace Clickpress\ContaoAssociationFormBundle\Controller\FrontendModule\ModuleAssociationForm;

use Contao\BackendTemplate;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\ModuleRegistration;
use Symfony\Component\HttpFoundation\RequestStack;

class ModuleAssociationFormController extends ModuleRegistration
{
    protected string $strTemplate = 'member_default';
    private $requestStack;
    private $scopeMatcher;

    public function __construct(RequestStack $requestStack, ScopeMatcher $scopeMatcher)
    {
        $this->requestStack = $requestStack;
        $this->scopeMatcher = $scopeMatcher;
    }

    public function generate()
    {
        if ($this->isBackend()) {
            $objTemplate = new BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### '.utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['association_form'][0]).' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    public function isBackend()
    {
        return $this->scopeMatcher->isBackendRequest($this->requestStack->getCurrentRequest());
    }

    public function isFrontend()
    {
        return $this->scopeMatcher->isFrontendRequest($this->requestStack->getCurrentRequest());
    }

    /**
     * Generate the module.
     */
    protected function compile(): void
    {
        parent::compile();
        $this->Template->slabel = $GLOBALS['TL_LANG']['FMD']['register_applicant']['button'];
    }
}
