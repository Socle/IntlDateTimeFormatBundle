<?php

namespace Socle\Bundle\IntlDateTimeFormatBundle\Twig;

use Socle\Bundle\IntlDateTimeFormatBundle\Formatter\IntlDateTimeFormatterInterface;

/**
 * Get locale date format in twig
 * 
 * @author Soliman Cheyssial <scheyssial@sqli.com>
 */
class DateTimeFormatExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var IntlDateTimeFormatterInterface
     */
    private $formatter;

    /**
     * Constructor
     *
     * @param IntlDateTimeFormatterInterface $formatter The DateTime formatter
     */
    public function __construct(IntlDateTimeFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('localized_date_format', array($this, 'getDateLocaleFormat')),
            new \Twig_SimpleFunction('localized_time_format', array($this, 'getTimeLocaleFormat')),
            new \Twig_SimpleFunction('localized_datetime_format', array($this, 'getDateTimeLocaleFormat'))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('localized_date', array($this, 'formatDate')),
            new \Twig_SimpleFilter('localized_time', array($this, 'formatTime')),
            new \Twig_SimpleFilter('localized_datetime', array($this, 'formatDateTime'))
        );
    }

    /**
     * @see IntlDateTimeFormatterInterface::getDateLocaleFormat()
     */
    public function getDateLocaleFormat($locale = null, $dateType = null, $alphaOnly = false)
    {
        return $this->formatter->getDateLocaleFormat($locale, $dateType, $alphaOnly);
    }

    /**
     * @see IntlDateTimeFormatterInterface::getTimeLocaleFormat()
     */
    public function getTimeLocaleFormat($locale = null, $timeType = null, $alphaOnly = false)
    {
        return $this->formatter->getTimeLocaleFormat($locale, $timeType, $alphaOnly);
    }

    /**
     * @see IntlDateTimeFormatterInterface::getDateTimeLocaleFormat()
     */
    public function getDateTimeLocaleFormat($locale = null, $dateType = null, $timeType = null, $alphaOnly = false)
    {
        return $this->formatter->getDateTimeLocaleFormat($locale, $dateType, $timeType, $alphaOnly);
    }

    /**
     * @see IntlDateTimeFormatterInterface::formatDate()
     */
    public function formatDate($value, $locale = null, $dateType = null, $timeZone = null)
    {
        return $this->formatter->formatDate($value, $locale, $dateType, $timeZone);
    }

    /**
     * @see IntlDateTimeFormatterInterface::formatTime
     */
    public function formatTime($value, $locale = null, $timeType = null, $timeZone = null)
    {
        return $this->formatter->formatTime($value, $locale, $timeType, $timeZone);
    }

    /**
     * @see IntlDateTimeFormatterInterface::formatDateTime
     */
    public function formatDateTime($value, $locale = null, $dateType = null, $timeType = null, $timeZone = null)
    {
        return $this->formatter->formatDateTime($value, $locale, $dateType, $timeType, $timeZone);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'socle_intl_date_time_format_extension';
    }
}
