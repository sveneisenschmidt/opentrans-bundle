<?php
/**
 * This file is part of the OpenTrans php library
 *
 * (c) Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SE\Bundle\OpenTransBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 *
 * @package SE\Bundle\OpenTransBundle
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $builder->root('se_open_trans')
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('documents')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('loader')
                                ->prototype('scalar')->end()
                            ->end()
                            ->scalarNode('type')
                                ->isRequired()
                            ->end()
                            ->variableNode('document')
                                ->isRequired()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $builder;
    }
}