<?php

namespace FluentPhp\Dates;

use Carbon\Carbon;

/**
 * An extension to Carbon (a simple PHP API extension for DateTime)
 * which adds fluent formatting. This allows you to format your
 * Carbon DateTime instances in in the following fashion:
 *
 * Date::parse(1457223530)->like('25th December 2016')
 *
 * Rather than
 *
 * Carbon::parse(1457223530)->format('jS F Y')
 *
 * Note: This is experimental and requires a certain degree of
 * conflict awareness to avoid formats being parsed incorrectly
 * (such as with month and day backwards).
 */
class Date extends Carbon
{
    /**
     * Array of all of the available date parsers.
     * 
     * @var array
     */
    private $parsers = [
        'DateString',
    ];

    /**
     * Parse the provided date as a Date format and then
     * format the date instance to match.
     *
     * @param string $date
     *
     * @return self
     */
    public function like($date = null)
    {
        $this->setToStringFormat($date);

        return $this->format(static::$toStringFormat);
    }

    /**
     * Set the default format used when type juggling the
     * Date instance to a string as the parsed date format.
     *
     * @param string $date
     *
     * @return void
     */
    public static function setToStringFormat($date)
    {
        static::$toStringFormat = (new static)->parseFormat($date);
    }

    /**
     * Attempt to start parsing a date format.
     * 
     * @param  string $date
     * 
     * @return string       
     */
    private function parseFormat($date)
    {
        foreach ($this->parsers as $parser) {
            if ($format = call_user_func_array([$this, 'parse'.$parser], [$date])) {
                return $format;
            }
        }

        return static::$toStringFormat;
    }

    /**
     * Parses and determines all Date Strings in the Format
     * of YYYY|**|**,  where `|` represents a separator 
     * and `*` represents a decimal.
     * 
     * @param  string $date 
     * 
     * @return string|void
     */
    private function parseDateString($date)
    {
        if (preg_match("/[0-9]{4}[\/\-\s](0[1-9]|[1-2][0-9]|3[0-1])[\/\-\s](0[1-9]|[1-2][0-9]|3[0-1])/", $date, $matches)) {
            $separator = substr($date, 4, 1);

            if ($matches[1] > 12) {
                return 'Y'.$separator.'d'.$separator.'m';
            } else if ($matches[2] > 12) {
                return 'Y'.$separator.'m'.$separator.'d';
            }

            if ($matches[1] > $matches[2]) {
                return 'Y'.$separator.'d'.$separator.'m';
            } 

            return 'Y'.$separator.'m'.$separator.'d';
        }
    }
}
