<?php

namespace Socle\Bundle\IntlDateTimeFormatBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle configuration
 *
 * @author Soliman Cheyssial <scheyssial@sqli.com>
 * @license http://opensource.org/licenses/BSD-3-Clause new BSD
 * @copyright 2014 Soliman CHEYSSIAL
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('socle_intl_date_time_format');

        $rootNode
            ->children()
                ->append($this->addOverloadFormatConfiguration())
            ->end();

        return $treeBuilder;
    }

    protected function addOverloadFormatConfiguration()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('localized_formats');

        $types = array(
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::SHORT,
            \IntlDateFormatter::MEDIUM,
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::FULL
        );

        $node
            ->info('Locale to overload')
            ->useAttributeAsKey('locale')
            ->prototype('array')
                ->children()
                    ->arrayNode('date')
                        ->prototype('scalar')->end()
                        ->defaultValue(array())
                        ->info('Formats related to type')
                        ->example('short: dd/MM/yy')
                        ->validate()
                            ->ifTrue(function($v) use ($types) {
                                return !in_array(key($v), $types);
                            })
                        ->thenInvalid('Invalid format type "%s"')
                        ->end()
                    ->end()
                    ->arrayNode('time')
                        ->prototype('scalar')->end()
                        ->defaultValue(array())
                        ->info('Formats related to type')
                        ->example('full: HH:mm:ss zzzz')
                        ->validate()
                            ->ifTrue(function($v) use ($types) {
                                return !in_array(key($v), $types);
                            })
                        ->thenInvalid('Invalid format type "%s"')
                    ->end()
                ->end()
            ->end();

        return $node;
    }
}
