<?php
declare(strict_types=1);

namespace AlexTartan\Helpers;

final class ConsoleHelper
{
    const COLOR_WARNING = 'black + yellow_bg + underline + bold';
    const COLOR_ERROR   = 'white + red_bg + underline + bold';

    private static $ansiCodes = [
        'off'        => 0,
        'bold'       => 1,
        'italic'     => 3,
        'underline'  => 4,
        'blink'      => 5,
        'inverse'    => 7,
        'hidden'     => 8,
        'black'      => 30,
        'red'        => 31,
        'green'      => 32,
        'yellow'     => 33,
        'blue'       => 34,
        'magenta'    => 35,
        'cyan'       => 36,
        'white'      => 37,
        'black_bg'   => 40,
        'red_bg'     => 41,
        'green_bg'   => 42,
        'yellow_bg'  => 43,
        'blue_bg'    => 44,
        'magenta_bg' => 45,
        'cyan_bg'    => 46,
        'white_bg'   => 47,
    ];

    public static function setColor(string $string, string $color): string
    {
        $colorAttrs = explode('+', $color);
        $ansiStr    = '';
        foreach ($colorAttrs as $attr) {
            $ansiStr .= "\033[" . self::$ansiCodes[trim($attr)] . 'm';
        }
        $ansiStr .= $string . "\033[" . self::$ansiCodes['off'] . 'm';

        return $ansiStr;
    }

    public static function echo(string $string, string $color)
    {
        echo self::setColor($string, $color);
    }
}
