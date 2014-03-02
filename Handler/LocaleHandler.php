<?php

namespace Socle\Bundle\IntlDateTimeFormatBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Locale handler class to get current locale
 * 
 * @author Soliman Cheyssial <scheyssial@sqli.com>
 * @license http://opensource.org/licenses/BSD-3-Clause new BSD
 * @copyright 2014 Soliman CHEYSSIAL
 */
class LocaleHandler implements LocaleHandlerInterface
{
    /**
     * @var string
     */
    protected $locale = 'en_US';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Class constructor
     *
     * @param ContainerInterface $container The current locale
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale($locale = null)
    {
        if (!empty($locale)) {
            return $locale;
        }

        if ($this->container !== null && $this->container->isScopeActive('request')) {
            return $this->container->get('request')->getLocale();
        }

        return $this->locale;
    }
} 