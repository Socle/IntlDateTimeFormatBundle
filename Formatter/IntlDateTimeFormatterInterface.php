<?php

namespace Socle\Bundle\IntlDateTimeFormatBundle\Formatter;

/**
 * Formatter class definition
 * 
 * @author Soliman Cheyssial <scheyssial@sqli.com>
 * @license http://opensource.org/licenses/BSD-3-Clause new BSD
 * @copyright 2014 Soliman CHEYSSIAL
 */
interface IntlDateTimeFormatterInterface
{
    const DEFAULT_TYPE = 'medium';

    /**
     * Formats a timestamp as date
     *
     * @param mixed  $value    Date value to be formatted
     * @param string $locale   Locale to be used with {@link http://php.net/manual/class.intldateformatter.php}
     * @param string $dateType Date format. Valid values are "full", "long", "medium", or "short" (case insensitive)
     * @param string $timeZone Time zone from {@link http://php.net/manual/timezones.php}
     *
     * @return string Formatted date
     */
    function formatDate($value, $locale = null, $dateType = null, $timeZone = null);

    /**
     * Formats a timestamp as time
     *
     * @param mixed  $value    Time value to be formatted
     * @param string $locale   Locale to be used with {@link http://php.net/manual/class.intldateformatter.php}
     * @param string $timeType Time format. Valid values are "full", "long", "medium", or "short" (case insensitive)
     * @param string $timeZone Time zone from {@link http://php.net/manual/timezones.php}
     *
     * @return string Formatted time
     */
    function formatTime($value, $locale = null, $timeType = null, $timeZone = null);

    /**
     * Formats a timestamp as date and time
     *
     * @param mixed  $value    Date/time value to be formatted
     * @param string $locale   Locale to be used with {@link http://php.net/manual/class.intldateformatter.php}
     * @param string $dateType Date format. Valid values are "none", "full", "long", "medium", or "short" (case insensitive)
     * @param string $timeType Time format. Valid values are "none", "full", "long", "medium", or "short" (case insensitive)
     * @param string $timeZone Time zone from {@link http://php.net/manual/timezones.php}
     *
     * @return string Formatted date and time
     *
     */
    function formatDateTime($value, $locale = null, $dateType = null, $timeType = null, $timeZone = null);

    /**
     * Format a date according to locale
     *
     * @param \DateTime|string $date     DateTime value to be formatted
     * @param string           $locale   The locale from which getting format
     * @param string           $dateType Date format. Valid values are "full", "long", "medium", or "short" (case insensitive)
     * @param string           $timeType Time format. Valid values are "none", "full", "long", "medium", or "short" (case insensitive)
     * @param string           $timeZone Time zone from {@link http://php.net/manual/timezones.php}
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    function getFormattedDateTime($date, $locale = null, $dateType = null, $timeType = null, $timeZone = null);

    /**
     * Get timezone
     *
     * @param mixed $timeZone The timezone
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    function getTimeZone($timeZone);

    /**
     * Get format type
     *
     * @param string $format Date/time format. Valid values are "none", "full", "long", "medium", or "short" (case insensitive)
     *
     * @return integer Appropriate value of {@link http://php.net/manual/class.intldateformatter.php#intl.intldateformatter-constants}
     *
     * @throws \InvalidArgumentException
     */
    function getDateFormatterFormat($format);

    /**
     * Get the format value for the key type
     *
     * @param string $locale           The locale to search
     * @param string $type             The type to get
     * @param string $key              The key (date or time) to get
     * @param array  $localizedFormats The format table
     *
     * @return string
     */
    function getTypeFormat($locale, $type, $key, $localizedFormats = null);

    /**
     * Check if the given locale is surcharged
     *
     * @param string $locale           The locale to search
     * @param array  $localizedFormats The format table
     *
     * @return boolean
     */
    function isLocaleSurcharged($locale = null, $localizedFormats = null);

    /**
     * Get format for surcharged locale
     *
     * @param string $locale           The locale from which getting format
     * @param string $dateType         Date format. Valid values are "full", "long", "medium", or "short" (case insensitive)
     * @param string $timeType         Time format. Valid values are "none", "full", "long", "medium", or "short" (case insensitive)
     * @param array  $localizedFormats The format table
     *
     * @return string
     */
    function getLocaleSurchargedDatetimeFormat($locale = null, $dateType = null, $timeType = null, $localizedFormats = null);

    /**
     * Get date format for given locale
     *
     * @param string  $locale    Locale to be used with {@link http://php.net/manual/class.intldateformatter.php}
     * @param string  $dateType  Date format. Valid values are "full", "long", "medium", or "short" (case insensitive)
     * @param boolean $alphaOnly If format must be only alphanumerical. Ex: dd/MM/yyyy => ddMMyyyy
     *
     * @return string
     */
    public function getDateLocaleFormat($locale = null, $dateType = null, $alphaOnly = false);

    /**
     * Get time format for given locale
     *
     * @param string  $locale    Locale to be used with {@link http://php.net/manual/class.intldateformatter.php}
     * @param string  $timeType  Time format. Valid values are "none", "full", "long", "medium", or "short" (case insensitive)
     * @param boolean $alphaOnly If format must be only alphanumerical. Ex: HH:mm:ss => HHmmss
     *
     * @return string
     */
    public function getTimeLocaleFormat($locale = null, $timeType = null, $alphaOnly = false);

    /**
     * Get datetime format for given locale
     *
     * @param string  $locale    The locale from which getting format
     * @param string  $dateType  Date format. Valid values are "full", "long", "medium", or "short" (case insensitive)
     * @param string  $timeType  Time format. Valid values are "none", "full", "long", "medium", or "short" (case insensitive)
     * @param boolean $alphaOnly If format must be only alphanumerical. Ex: dd/MM/yyyy HH:mm:ss => ddMMyyyyHHmmss
     *
     * @return string
     */
    public function getDateTimeLocaleFormat($locale = null, $dateType = null, $timeType = null, $alphaOnly = false);

    /**
     * Get datetime format for given locale
     *
     * @param string $locale   The locale from which getting format
     * @param string $dateType Date format. Valid values are "full", "long", "medium", or "short" (case insensitive)
     * @param string $timeType Time format. Valid values are "none", "full", "long", "medium", or "short" (case insensitive)
     *
     * @return string
     */
    public function getLocalizedDateTimeFormat($locale = null, $dateType = null, $timeType = null);

    /**
     * Get default datetime format for given locale
     *
     * @param string $locale   The locale from which getting format
     * @param string $dateType Date format. Valid values are "full", "long", "medium", or "short" (case insensitive)
     * @param string $timeType Time format. Valid values are "none", "full", "long", "medium", or "short" (case insensitive)
     *
     * @return string
     */
    public function getDefaultLocalizedFormat($locale = null, $dateType = null, $timeType = null);

    /**
     * Get default type if not defined
     *
     * @param string  $type      The original type
     * @param boolean $formatter Whether to use formatter format
     *
     * @return string
     */
    function getType($type = null, $formatter = false);
} 