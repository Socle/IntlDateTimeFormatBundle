<?php

namespace Socle\Bundle\IntlDateTimeFormatBundle\Formatter;

use Socle\Bundle\IntlDateTimeFormatBundle\Handler\LocaleHandlerInterface;

/**
 * Formatter class to get a formatted date or format according to locale
 * 
 * @author Soliman Cheyssial <scheyssial@sqli.com>
 * @license http://opensource.org/licenses/BSD-3-Clause new BSD
 * @copyright 2014 Soliman CHEYSSIAL
 */
class IntlDateTimeFormatter implements IntlDateTimeFormatterInterface
{
    /**
     * @var integer
     */
    protected $dateType = \IntlDateFormatter::MEDIUM;

    /**
     * @var integer
     */
    protected $timeType = \IntlDateFormatter::MEDIUM;

    /**
     * @var array
     */
    protected $localizedFormats;

    /**
     * Class constructor
     *
     * @param LocaleHandlerInterface $localeHandler    The locale handler
     * @param array                  $localizedFormats The overloaded formats
     */
    public function __construct(LocaleHandlerInterface $localeHandler, $localizedFormats)
    {
        $this->localeHandler    = $localeHandler;
        $this->localizedFormats = $localizedFormats;
    }

    /**
     * {@inheritdoc}
     */
    public function formatDate($value, $locale = null, $dateType = null, $timeZone = null)
    {
        $this->validateType($dateType);

        return $this->getFormattedDateTime($value, $locale, $dateType, 'none', $timeZone);
    }

    /**
     * {@inheritdoc}
     */
    public function formatTime($value, $locale = null, $timeType = null, $timeZone = null)
    {
        $this->validateType($timeType);

        return $this->getFormattedDateTime($value, $locale, 'none', $timeType, $timeZone);
    }

    /**
     * {@inheritdoc}
     */
    public function formatDateTime($value, $locale = null, $dateType = null, $timeType = null, $timeZone = null)
    {
        $this->validateType($dateType, $timeType);

        return $this->getFormattedDateTime($value, $locale, $dateType, $timeType, $timeZone);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormattedDateTime($date, $locale = null, $dateType = null, $timeType = null, $timeZone = null)
    {
        if ($date === null) {
            return null;
        }

        $returningDate = $this->validateString($date);

        if ($returningDate instanceof \DateTime) {
            // Get timestamp
            $returningDate = floatval($returningDate->format('U'));
        }

        $locale = $this->localeHandler->getLocale($locale);
        $dateTypeToUse = $this->getType($dateType, true);
        $timeTypeToUse = $this->getType($timeType, true);
        $timeZone = $this->getTimeZone($timeZone);

        $format = $this->getDateTimeLocaleFormat($locale, $dateType, $timeType);
        // Get the formatter
        $formatter = new \IntlDateFormatter(
            $locale, $dateTypeToUse, $timeTypeToUse, $timeZone, \IntlDateFormatter::GREGORIAN, $format
        );

        // Get date formatted
        $result = $formatter->format($returningDate);
        if (false === $result) {
            throw new \InvalidArgumentException(sprintf('The value "%s" of type %s cannot be formatted. Error: "%s".', $date, gettype($date), $formatter->getErrorMessage()));
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeZone($timeZone)
    {
        if (null === $timeZone) {
            $timeZone = new \DateTimeZone(date_default_timezone_get());
        } elseif (!($timeZone instanceof \DateTimeZone)) {
            try {
                $timeZone = new \DateTimeZone($timeZone);
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(sprintf('The "%s" value is not a supported time zone.', $timeZone));
            }
        }

        return $timeZone->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getDateFormatterFormat($format)
    {
        switch (strtoupper($format)) {
            case 'NONE':
                return \IntlDateFormatter::NONE;
            case 'FULL':
                return \IntlDateFormatter::FULL;
            case 'LONG':
                return \IntlDateFormatter::LONG;
            case 'MEDIUM':
                return \IntlDateFormatter::MEDIUM;
            case 'SHORT':
                return \IntlDateFormatter::SHORT;
            default:
                throw new \InvalidArgumentException(sprintf('The "%s" value is not a supported format.', $format));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeFormat($locale, $type, $key, $localizedFormats = null)
    {
        if (!isset($localizedFormats[$locale][$key]) || !isset($localizedFormats[$locale][$key][$type])) {
            return null;
        }

        // Verify if the type exists
        $this->getDateFormatterFormat($type);

        return $localizedFormats[$locale][$key][$type];
    }

    /**
     * {@inheritdoc}
     */
    public function isLocaleSurcharged($locale = null, $localizedFormats = null)
    {
        if (null === $localizedFormats) {
            $localizedFormats = $this->localizedFormats;
        }
        if (!is_array($localizedFormats) || !isset($localizedFormats[$locale])) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleSurchargedDatetimeFormat($locale = null, $dateType = null, $timeType = null, $localizedFormats = null)
    {
        if (null === $localizedFormats) {
            $localizedFormats = $this->localizedFormats;
        }
        $locale = $this->localeHandler->getLocale($locale);

        $dateType = $this->getType($dateType);
        $timeType = $this->getType($timeType);
        $dateFormat = null;
        $timeFormat = null;

        if ('none' !== strtolower($dateType)) {
            $dateFormat = $this->getTypeFormat($locale, $dateType, 'date', $localizedFormats);
            if (empty($dateFormat)) {
                $dateFormat = $this->getDefaultLocalizedFormat($locale, $dateType, 'none');
            }
        }
        if ('none' !== strtolower($timeType)) {
            $timeFormat = $this->getTypeFormat($locale, $timeType, 'time', $localizedFormats);
            if (empty($timeFormat)) {
                $timeFormat = $this->getDefaultLocalizedFormat($locale, 'none', $timeType);
            }
        }

        if (empty($dateFormat)) {
            return $timeFormat;
        } elseif (empty($timeFormat)) {
            return $dateFormat;
        }

        return $dateFormat . ' ' . $timeFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function getDateLocaleFormat($locale = null, $dateType = null, $alphaOnly = false)
    {
        return $this->getDateTimeLocaleFormat($locale, $dateType, 'none', $alphaOnly);
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeLocaleFormat($locale = null, $timeType = null, $alphaOnly = false)
    {
        return $this->getDateTimeLocaleFormat($locale, 'none', $timeType, $alphaOnly);
    }

    /**
     * {@inheritdoc}
     */
    public function getDateTimeLocaleFormat($locale = null, $dateType = null, $timeType = null, $alphaOnly = false)
    {
        $this->validateType($dateType, $timeType);

        $format = $this->getLocalizedDateTimeFormat($locale, $dateType, $timeType);
        if ($alphaOnly) {
            $format = preg_replace('#[^a-zA-Z]+#', '', $format);
        }

        return $format;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocalizedDateTimeFormat($locale = null, $dateType = null, $timeType = null)
    {
        $locale = $this->localeHandler->getLocale($locale);

        if (strlen($locale) > 2) {
            $locale = substr($locale, 0, 2);
        }

        $localizedFormats = $this->localizedFormats;

        if ($this->isLocaleSurcharged($locale, $localizedFormats)) {
            $format = $this->getLocaleSurchargedDatetimeFormat($locale, $dateType, $timeType, $localizedFormats);
        } else {
            $format = $this->getDefaultLocalizedFormat($locale, $dateType, $timeType);
        }

        return $format;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultLocalizedFormat($locale = null, $dateType = null, $timeType = null)
    {
        $dateTypeToUse = $this->getType($dateType, true);
        $timeTypeToUse = $this->getType($timeType, true);

        $formatter = new \IntlDateFormatter(
            $locale, $dateTypeToUse, $timeTypeToUse, null, \IntlDateFormatter::GREGORIAN
        );

        return $formatter->getPattern();
    }

    /**
     * {@inheritdoc}
     */
    public function getType($type = null, $formatter = false)
    {
        if (empty($type)) {
            $type = static::DEFAULT_TYPE;
        }

        if ($formatter) {
            return $this->getDateFormatterFormat($type);
        }

        return $type;
    }

    /**
     * Validate one or two format types
     *
     * @param string         $type       The type to validate
     * @param string|boolean $secondType If not false, the second type to validate
     *
     * @return boolean
     *
     * @throws \InvalidArgumentException
     */
    protected function validateType($type, $secondType = false)
    {
        if ('none' === $type) {
            if (false !== $secondType) {
                $this->validateType($secondType);
            } else {
                throw new \InvalidArgumentException(sprintf('The "%s" value is not a supported type.', $type));
            }
        }

        return true;
    }

    /**
     * Validate and transform date value if "$date" is a string
     *
     * @param mixed $date The date value to validate
     *
     * @return \DateTime|float
     *
     * @throws \InvalidArgumentException
     */
    protected function validateString($date)
    {
        $returningDate = $date;
        // If given date is string
        if (is_string($returningDate)) {
            // Check whether the string contains only digits
            if (ctype_digit($returningDate)) {
                $returningDate = floatval($returningDate);
            } else {
                try {
                    $returningDate = new \DateTime($returningDate);
                } catch (\Exception $e) {
                    throw new \InvalidArgumentException(
                        sprintf('The value "%s" of type %s is invalid. Error: "%s".', $date, gettype($date), $e->getMessage()));
                }
            }
        }

        return $returningDate;
    }
}
