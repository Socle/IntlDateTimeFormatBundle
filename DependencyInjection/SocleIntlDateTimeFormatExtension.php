<?php

namespace Socle\Bundle\IntlDateTimeFormatBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SocleIntlDateTimeFormatExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('socle_intl_date_time_format.localized_formats', $this->getLocalizedFormat($config));

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * Get the "socle_intl_date_time_format.localized_format" parameter value from configuration
     *
     * @param array $config The processed configuration
     *
     * @return array
     */
    private function getLocalizedFormat($config)
    {
        if (isset($config['localized_formats'])) {
            return $config['localized_formats'];
        }

        return array();
    }
}
