<?php
declare(strict_types=1);

namespace AlexTartan\Helpers;

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
}
