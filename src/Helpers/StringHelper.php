<?php

declare(strict_types=1);

namespace AlexTartan\Helpers;

use InvalidArgumentException;

use function random_int;

final class StringHelper
{

    public static function stripNonPrintableCharacters(string $value): string
    {
        return (string)preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
    }

    public static function sortAlphabetically(string $string): string
    {
        $arr = str_split($string, 1);
        sort($arr);

        return implode('', $arr);
    }

    public static function generateRandomString(
        int $length,
        bool $useLower = true,
        bool $useUpper = true,
        bool $useDigits = true,
        bool $useSpecials = true
    ): string {
        $alphaLower = 'abcdefghijklmnopqrstuvwxyz';
        $alphaUpper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits = '1234567890';
        $special = ',.?!@#$%^&*()_+-=[]{}<>';

        $keyspace = ''
                    . ($useLower ? $alphaLower : '')
                    . ($useUpper ? $alphaUpper : '')
                    . ($useDigits ? $digits : '')
                    . ($useSpecials ? $special : '');

        $max = mb_strlen($keyspace) - 1;
        if ($max < 1) {
            throw new InvalidArgumentException('keyspace must be at least two characters long');
        }
        if ($length < count(array_filter([$useLower, $useUpper, $useDigits, $useSpecials]))) {
            throw new InvalidArgumentException(
                'Length cannot accommodate your requirements. Please increase accordingly.'
            );
        }

        $str = ''
               . ($useLower ? $alphaLower[random_int(0, strlen($alphaLower) - 1)] : '')
               . ($useUpper ? $alphaUpper[random_int(0, strlen($alphaUpper) - 1)] : '')
               . ($useDigits ? $digits[random_int(0, strlen($digits) - 1)] : '')
               . ($useSpecials ? $special[random_int(0, strlen($special) - 1)] : '');

        for ($i = strlen($str); $i < $length; $i++) {
            $str .= $keyspace[random_int(0, $max)];
        }

        return str_shuffle($str);
    }
}
