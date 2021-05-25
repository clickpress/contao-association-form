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

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Widget;

/**
 * @Hook("addCustomRegexp")
 */
class AddCustomRegexpListener
{
    /**
     * Check IBAN.
     *
     * @Hook("addCustomRegexp")
     */
    public function checkIban(string $regexp, $input, Widget $objWidget): bool
    {
        if ('iban' === $regexp) {
            $input = trim(str_replace(' ', '', $input));
            $expression = '^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$';

            if (!preg_match('/'.$expression.'/', $varValue)) {
                $objWidget->addError('Bitte eine gültige IBAN angeben');
            }

            return true;
        }

        return false;
    }

    /**
     * Check BIC.
     *
     * @Hook("addCustomRegexp")
     */
    public function checkBic(string $regexp, $input, Widget $objWidget): bool
    {
        if ('bic' === $regexp) {
            if (!preg_match('/^[a-z]{6}[0-9a-z]{2}([0-9a-z]{3})?\z/i', $input)) {
                $objWidget->addError('Bitte eine gültige BIC angeben');
            }

            return true;
        }

        return false;
    }
}
