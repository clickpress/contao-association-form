<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoAssociationFormBundle for Contao.
 *
 * (c) Stefan Schulz-Lauterbach
 *
 * @license LGPL-3.0-or-later
 */

namespace Clickpress\ContaoAssociationFormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('contao_association_form');
        $treeBuilder->getRootNode()
            ->children()
            ->arrayNode('test')
            ->children()
            ->integerNode('foo')->end()
            ->scalarNode('bar')->end()
            ->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
