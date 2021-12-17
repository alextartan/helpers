<?php

declare(strict_types=1);

namespace AlexTartan\Helpers;

use Exception;
use InvalidArgumentException;

use function random_int;

final class StringHelper
{
    private const KEYSPACE_ALPHA_LOWER = 'abcdefghijklmnopqrstuvwxyz';
    private const KEYSPACE_ALPHA_UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const KEYSPACE_DIGITS      = '1234567890';
    private const KEYSPACE_SPECIAL     = ',.?!@#$%^&*()_+-=[]{}<>';

    public static function stripNonPrintableCharacters(string $value): string
    {
        return (string) preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
    }

    public static function sortAlphabetically(string $string): string
    {
        $arr = str_split($string);
        sort($arr);

        return implode('', $arr);
    }

    /** @throws Exception */
    public static function generateRandomString(
        int $length,
        bool $useLower = true,
        bool $useUpper = true,
        bool $useDigits = true,
        bool $useSpecials = true
    ): string {
        $keyspace = self::getKeyspace($useLower, $useUpper, $useDigits, $useSpecials);
        $max      = mb_strlen($keyspace) - 1;
        if ($max < 1) {
            throw new InvalidArgumentException(
                'keyspace must be at least two characters long'
            );
        }
        if ($length < count(array_filter([$useLower, $useUpper, $useDigits, $useSpecials]))) {
            throw new InvalidArgumentException(
                'Length cannot accommodate your requirements. Please increase accordingly.'
            );
        }

        $str = ''
            . ($useLower
                ? self::KEYSPACE_ALPHA_LOWER[random_int(0, strlen(self::KEYSPACE_ALPHA_LOWER) - 1)]
                : '')
            . ($useUpper
                ? self::KEYSPACE_ALPHA_UPPER[random_int(0, strlen(self::KEYSPACE_ALPHA_UPPER) - 1)]
                : '')
            . ($useDigits
                ? self::KEYSPACE_DIGITS[random_int(0, strlen(self::KEYSPACE_DIGITS) - 1)]
                : '')
            . ($useSpecials
                ? self::KEYSPACE_SPECIAL[random_int(0, strlen(self::KEYSPACE_SPECIAL) - 1)]
                : '');

        for ($i = strlen($str); $i < $length; $i++) {
            $str .= $keyspace[random_int(0, $max)];
        }

        return str_shuffle($str);
    }

    private static function getKeyspace(
        bool $useLower,
        bool $useUpper,
        bool $useDigits,
        bool $useSpecials
    ): string {
        return ''
            . ($useLower
                ? self::KEYSPACE_ALPHA_LOWER
                : '')
            . ($useUpper
                ? self::KEYSPACE_ALPHA_UPPER
                : '')
            . ($useDigits
                ? self::KEYSPACE_DIGITS
                : '')
            . ($useSpecials
                ? self::KEYSPACE_SPECIAL
                : '');
    }
}
