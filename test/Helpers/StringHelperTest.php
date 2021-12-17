<?php

declare(strict_types=1);

namespace AlexTartanTest\Helpers;

use AlexTartan\Helpers\StringHelper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AlexTartan\Helpers\StringHelper
 */
final class StringHelperTest extends TestCase
{

    /** @dataProvider stripDataProvider */
    public function testStripNonPrintableCharactersReturnsPrintable(string $in, string $out): void
    {
        $strippedString = StringHelper::stripNonPrintableCharacters($in);
        self::assertEquals($out, $strippedString);
    }

    /** @return string[][] */
    public function stripDataProvider(): array
    {
        return [
            ["\e ABCDabcd_`~¶µÅÆÇÐØ÷", ' ABCDabcd_`~¶µÅÆÇÐØ÷'],
            ["\e", ''],
        ];
    }

    public function testSortAlphabetically(): void
    {
        self::assertEquals('acdg', StringHelper::sortAlphabetically('agdc'));
        self::assertEquals('1acg', StringHelper::sortAlphabetically('g1ac'));
        self::assertEquals(' adz', StringHelper::sortAlphabetically('az d'));
    }

    public function testRandomStringGeneratorThrowsExceptionOnEmptyKeyspace(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('keyspace must be at least two characters long');

        StringHelper::generateRandomString(16, false, false, false, false);
    }

    /** @dataProvider lenTooShortDataProvider */
    public function testRandomStringGeneratorThrowsExceptionOnLengthTooSmall(int $len): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Length cannot accommodate your requirements. Please increase accordingly.');

        StringHelper::generateRandomString($len);
    }

    public function lenTooShortDataProvider(): array
    {
        return [
            [-1],
            [0],
            [1],
            [2],
            [3],
        ];
    }

    public function testRandomStringGeneratorLength(): void
    {
        for ($i = 16; $i < 32; $i++) {
            self::assertSame(16, mb_strlen(StringHelper::generateRandomString(16)));
        }
    }

    public function testRandomStringGeneratorContainsCorrectKeySpaces(): void
    {
        // test contains ONLY lowercase
        $pass = StringHelper::generateRandomString(16, true, false, false, false);
        self::assertMatchesRegularExpression('/[a-z]/', $pass);
        self::assertDoesNotMatchRegularExpression('/[A-Z]/', $pass);
        self::assertDoesNotMatchRegularExpression('/[0-9]/', $pass);
        self::assertDoesNotMatchRegularExpression('/[\!\@\#\$\%\^\&\*\(\)\_\+\-\=\[\]\{\}\<\>\,\.\?]/', $pass);

        // test contains ONLY uppercase
        $pass = StringHelper::generateRandomString(16, false, true, false, false);
        self::assertDoesNotMatchRegularExpression('/[a-z]/', $pass);
        self::assertMatchesRegularExpression('/[A-Z]/', $pass);
        self::assertDoesNotMatchRegularExpression('/[0-9]/', $pass);
        self::assertDoesNotMatchRegularExpression('/[\!\@\#\$\%\^\&\*\(\)\_\+\-\=\[\]\{\}\<\>\,\.\?]/', $pass);

        // test contains ONLY numbers
        $pass = StringHelper::generateRandomString(16, false, false, true, false);
        self::assertDoesNotMatchRegularExpression('/[a-z]/', $pass);
        self::assertDoesNotMatchRegularExpression('/[A-Z]/', $pass);
        self::assertMatchesRegularExpression('/[0-9]/', $pass);
        self::assertDoesNotMatchRegularExpression('/[\!\@\#\$\%\^\&\*\(\)\_\+\-\=\[\]\{\}\<\>\,\.\?]/', $pass);

        // test contains ONLY specials
        $pass = StringHelper::generateRandomString(16, false, false, false, true);
        self::assertDoesNotMatchRegularExpression('/[a-z]/', $pass);
        self::assertDoesNotMatchRegularExpression('/[A-Z]/', $pass);
        self::assertDoesNotMatchRegularExpression('/[0-9]/', $pass);
        self::assertMatchesRegularExpression('/[\!\@\#\$\%\^\&\*\(\)\_\+\-\=\[\]\{\}\<\>\,\.\?]/', $pass);

        // test contains ALL spaces (by default
        $pass = StringHelper::generateRandomString(16);
        self::assertMatchesRegularExpression('/[a-z]/', $pass);
        self::assertMatchesRegularExpression('/[A-Z]/', $pass);
        self::assertMatchesRegularExpression('/[0-9]/', $pass);
        self::assertMatchesRegularExpression('/[\!\@\#\$\%\^\&\*\(\)\_\+\-\=\[\]\{\}\<\>\,\.\?]/', $pass);
    }
}
