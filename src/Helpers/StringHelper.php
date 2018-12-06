<?php
declare(strict_types=1);

namespace AlexTartan\Helpers;

use InvalidArgumentException;

final class StringHelper
{
    public static function stripNonPrintableCharacters(string $value): string
    {
        $result = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
        if ($result === null) {
            return '';
        }

        return $result;
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
        $digits     = '1234567890';
        $special    = '!@#$%^&*()_+-=[]{}<>';

        $keyspace = str_shuffle(
            ''
            . ($useLower ? $alphaLower : '')
            . ($useUpper ? $alphaUpper : '')
            . ($useDigits ? $digits : '')
            . ($useSpecials ? $special : '')
        );

        $max = mb_strlen($keyspace) - 1;
        if ($max < 1) {
            throw new InvalidArgumentException('keyspace must be at least two characters long');
        }
        if ($length < count(array_filter([$useLower, $useUpper, $useDigits, $useSpecials]))) {
            throw new InvalidArgumentException('Length cannot accommodate your requirements. Please increase accordingly.');
        }

        do {
            $str = '';
            for ($i = 0; $i < $length; $i++) {
                $str .= $keyspace[random_int(0, $max)];
            }
            $allDictionariesUsed =
                ($useLower ? preg_match('/[a-z]/', $str) : true) &&
                ($useUpper ? preg_match('/[A-Z]/', $str) : true) &&
                ($useDigits ? preg_match('/[\d]/', $str) : true) &&
                ($useSpecials ? preg_match('/[[!@#\$%\^&*\(\)_+-=\[\]\{\}<>]]/', $str) : true);
        } while (!$allDictionariesUsed);

        return $str;
    }
}
