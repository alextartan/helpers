<?php

declare(strict_types=1);

namespace AlexTartan\Helpers;

use function array_reduce;
use function explode;
use function trim;

final class ConsoleHelper
{
    public const COLOR_SUCCESS = 'green + black_bg + underline + bold';
    public const COLOR_WARNING = 'black + yellow_bg + underline + bold';
    public const COLOR_ERROR   = 'white + red_bg + underline + bold';

    /** @var int[] */
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
        $callback = static function (string $carry, string $item) {
            return $carry . "\033[" . self::$ansiCodes[trim($item)] . 'm';
        };

        return
            array_reduce(
                explode('+', $color),
                $callback,
                ''
            )
            . $string
            . "\033[" . self::$ansiCodes['off'] . 'm'; // "\e[" + CODE + "m"
    }

    public static function echo(string $string, string $color): void
    {
        echo self::setColor($string, $color);
    }
}
