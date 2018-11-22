<?php
declare(strict_types=1);

namespace AlexTartanTest\Helpers;

use AlexTartan\Helpers\StringHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AlexTartan\Helpers\StringHelper
 */
final class StringHelperTest extends TestCase
{
    public function testStripNonPrintableCharacters()
    {
        $input          = "\e ABCDabcd_`~¶µÅÆÇÐØ÷";
        $expectedString = ' ABCDabcd_`~¶µÅÆÇÐØ÷';
        $strippedString = StringHelper::stripNonPrintableCharacters($input);
        self::assertEquals($strippedString, $expectedString);
    }

    public function testSortAlphabetically()
    {
        self::assertEquals('acdg', StringHelper::sortAlphabetically('agdc'));
        self::assertEquals('1acg', StringHelper::sortAlphabetically('g1ac'));
        self::assertEquals(' adz', StringHelper::sortAlphabetically('az d'));
    }
}
