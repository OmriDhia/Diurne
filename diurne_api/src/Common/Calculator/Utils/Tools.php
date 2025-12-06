<?php

namespace App\Common\Calculator\Utils;

class Tools
{
    public const PRICE_ROUND_MODE = 2;
    public const ROUND_UP = 0;
    public const ROUND_DOWN = 1;
    public const ROUND_HALF_UP = 2;
    public const ROUND_HALF_DOWN = 3;
    public const ROUND_HALF_EVEN = 4;
    public const ROUND_HALF_ODD = 5;
    public static $round_mode;

    /**
     * returns the rounded value of $value to specified precision, according to your configuration;.
     *
     * @note : PHP 5.3.0 introduce a 3rd parameter mode in round function
     *
     * @param float $value
     * @param int   $precision
     *
     * @return false|float
     */
    public static function ps_round($value, $precision = 3, $round_mode = null): float|false
    {
        if (null === $round_mode) {
            if (null == Tools::$round_mode) {
                Tools::$round_mode = self::PRICE_ROUND_MODE;
            }

            $round_mode = Tools::$round_mode;
        }

        return match ($round_mode) {
            self::ROUND_UP => Tools::ceilf($value, $precision),
            self::ROUND_DOWN => Tools::floorf($value, $precision),
            self::ROUND_HALF_DOWN, self::ROUND_HALF_EVEN, self::ROUND_HALF_ODD => Tools::math_round($value, $precision, $round_mode),
            default => Tools::math_round($value, $precision, self::ROUND_HALF_UP),
        };
    }

    /**
     * Returns the rounded value up of $value to specified precision.
     *
     * @param float $value
     * @param int   $precision
     *
     * @return float
     */
    public static function ceilf($value, $precision = 0)
    {
        $precision_factor = 0 == $precision ? 1 : 10 ** $precision;
        $tmp = $value * $precision_factor;
        $tmp2 = (string) $tmp;
        // If the current value has already the desired precision
        if (!str_contains($tmp2, '.')) {
            return $value;
        }
        if (0 == $tmp2[strlen($tmp2) - 1]) {
            return $value;
        }

        return ceil($tmp) / $precision_factor;
    }

    /**
     * Returns the rounded value down of $value to specified precision.
     *
     * @param float $value
     * @param int   $precision
     *
     * @return float
     */
    public static function floorf($value, $precision = 0)
    {
        $precision_factor = 0 == $precision ? 1 : 10 ** $precision;
        $tmp = $value * $precision_factor;
        $tmp2 = (string) $tmp;
        // If the current value has already the desired precision
        if (!str_contains($tmp2, '.')) {
            return $value;
        }
        if (0 == $tmp2[strlen($tmp2) - 1]) {
            return $value;
        }

        return floor($tmp) / $precision_factor;
    }

    /**
     * @param int|float $value
     * @param int|float $places
     * @param int<2,5>  $mode   (ROUND_HALF_UP|ROUND_HALF_DOWN|ROUND_HALF_EVEN|ROUND_HALF_ODD)
     *
     * @return false|float
     */
    public static function math_round($value, $places, $mode = self::ROUND_HALF_UP)
    {
        // If PHP_ROUND_HALF_UP exist (PHP 5.3) use it and pass correct mode value (PrestaShop define - 1)
        if (defined('PHP_ROUND_HALF_UP')) {
            return round($value, $places, $mode - 1);
        }

        $precision_places = 14 - floor(log10(abs($value)));
        $f1 = 10.0 ** (float) abs($places);

        /* If the decimal precision guaranteed by FP arithmetic is higher than
        * the requested places BUT is small enough to make sure a non-zero value
        * is returned, pre-round the result to the precision */
        if ($precision_places > $places && $precision_places - $places < 15) {
            $f2 = 10.0 ** (float) abs($precision_places);

            if ($precision_places >= 0) {
                $tmp_value = $value * $f2;
            } else {
                $tmp_value = $value / $f2;
            }

            /* preround the result (tmp_value will always be something * 1e14,
            * thus never larger than 1e15 here) */
            $tmp_value = Tools::round_helper($tmp_value, $mode);
            /* now correctly move the decimal point */
            $f2 = 10.0 ** (float) abs($places - $precision_places);
            /* because places < precision_places */
            $tmp_value = $tmp_value / $f2;
        } else {
            /* adjust the value */
            if ($places >= 0) {
                $tmp_value = $value * $f1;
            } else {
                $tmp_value = $value / $f1;
            }

            /* This value is beyond our precision, so rounding it is pointless */
            if (abs($tmp_value) >= 1e15) {
                return $value;
            }
        }

        /* round the temp value */
        $tmp_value = Tools::round_helper($tmp_value, $mode);

        /* see if it makes sense to use simple division to round the value */
        if (abs($places) < 23) {
            if ($places > 0) {
                $tmp_value /= $f1;
            } else {
                $tmp_value *= $f1;
            }
        }

        return $tmp_value;
    }

    /**
     * @param float $value
     * @param int   $mode
     *
     * @return float
     */
    public static function round_helper($value, $mode)
    {
        if ($value >= 0.0) {
            $tmp_value = floor($value + 0.5);

            if ((self::ROUND_HALF_DOWN == $mode && $value == (-0.5 + $tmp_value))
                || (self::ROUND_HALF_EVEN == $mode && $value == (0.5 + 2 * floor($tmp_value / 2.0)))
                || (self::ROUND_HALF_ODD == $mode && $value == (0.5 + 2 * floor($tmp_value / 2.0) - 1.0))
            ) {
                $tmp_value = $tmp_value - 1.0;
            }
        } else {
            $tmp_value = ceil($value - 0.5);

            if ((self::ROUND_HALF_DOWN == $mode && $value == (0.5 + $tmp_value))
                || (self::ROUND_HALF_EVEN == $mode && $value == (-0.5 + 2 * ceil($tmp_value / 2.0)))
                || (self::ROUND_HALF_ODD == $mode && $value == (-0.5 + 2 * ceil($tmp_value / 2.0) + 1.0))
            ) {
                $tmp_value = $tmp_value + 1.0;
            }
        }

        return $tmp_value;
    }

    public static function slugify($text, string $divider = '-'): string
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, (string) $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', (string) $text);

        // remove unwanted characters
        $text = preg_replace('~[^\-\w]+~', '', $text);

        // trim
        $text = trim((string) $text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower((string) $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
