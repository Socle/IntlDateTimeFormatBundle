<?php

namespace Socle\Bundle\IntlDateTimeFormatBundle\Handler;

/**
 * Locale handler class definition
 * 
 * @author Soliman Cheyssial <scheyssial@sqli.com>
 */
interface LocaleHandlerInterface
{
    /**
     * Get the current locale
     *
     * @param string $locale The locale from which getting format
     *
     * @return string
     */
    public function getLocale($locale = null);
} 