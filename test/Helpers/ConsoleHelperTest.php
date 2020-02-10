<?php

declare(strict_types=1);

namespace AlexTartanTest\Helpers;

use AlexTartan\Helpers\ConsoleHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AlexTartan\Helpers\ConsoleHelper
 */
final class ConsoleHelperTest extends TestCase
{
    public function testEcho(): void
    {
        $text = 'TestText';
        ConsoleHelper::echo($text, ConsoleHelper::COLOR_ERROR);

        $this->expectOutputString("\e[37m\e[41m\e[4m\e[1m" . $text . "\e[0m");
    }

    public function testSetColor(): void
    {
        $text = 'TestText';

        $a = ConsoleHelper::setColor($text, ConsoleHelper::COLOR_ERROR);
        $b = ConsoleHelper::setColor($text, ConsoleHelper::COLOR_WARNING);
        $c = ConsoleHelper::setColor($text, ConsoleHelper::COLOR_SUCCESS);

        self::assertSame("\e[37m\e[41m\e[4m\e[1m" . $text . "\e[0m", $a);
        self::assertSame("\e[30m\e[43m\e[4m\e[1m" . $text . "\e[0m", $b);
        self::assertSame("\e[32m\e[40m\e[4m\e[1m" . $text . "\e[0m", $c);
    }
}
