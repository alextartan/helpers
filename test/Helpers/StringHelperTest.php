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
    public function testStripNonPrintableCharacters(): void
    {
        $input          = "\e ABCDabcd_`~¶µÅÆÇÐØ÷";
        $expectedString = ' ABCDabcd_`~¶µÅÆÇÐØ÷';
        $strippedString = StringHelper::stripNonPrintableCharacters($input);
        self::assertEquals($strippedString, $expectedString);
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

    public function testRandomStringGeneratorContainsCorrectKeyspaces(): void
    {
        // test contains ONLY lowercase
        $pass = StringHelper::generateRandomString(16, true, false, false, false);
        self::assertRegExp('/[a-z]/', $pass);
        self::assertNotRegExp('/[A-Z]/', $pass);
        self::assertNotRegExp('/[0-9]/', $pass);
        self::assertNotRegExp('/[[!@#\$%\^&*\(\)_+-=\[\]\{\}<>]]/', $pass);

        // test contains ONLY uppercase
        $pass = StringHelper::generateRandomString(16, false, true, false, false);
        self::assertNotRegExp('/[a-z]/', $pass);
        self::assertRegExp('/[A-Z]/', $pass);
        self::assertNotRegExp('/[0-9]/', $pass);
        self::assertNotRegExp('/[[!@#\$%\^&*\(\)_+-=\[\]\{\}<>]]/', $pass);

        // test contains ONLY numbers
        $pass = StringHelper::generateRandomString(16, false, false, true, false);
        self::assertNotRegExp('/[a-z]/', $pass);
        self::assertNotRegExp('/[A-Z]/', $pass);
        self::assertRegExp('/[0-9]/', $pass);
        self::assertNotRegExp('/[[!@#\$%\^&*\(\)_+-=\[\]\{\}<>]]/', $pass);

        // test contains ONLY specials
        $pass = StringHelper::generateRandomString(16, false, false, false, true);
        self::assertNotRegExp('/[a-z]/', $pass);
        self::assertNotRegExp('/[A-Z]/', $pass);
        self::assertNotRegExp('/[0-9]/', $pass);
        self::assertRegExp('/[[!@#\$%\^&*\(\)_+-=\[\]\{\}<>]]/', $pass);

        // test contains ALL spaces (by default
        $pass = StringHelper::generateRandomString(16);
        self::assertRegExp('/[a-z]/', $pass);
        self::assertRegExp('/[A-Z]/', $pass);
        self::assertRegExp('/[0-9]/', $pass);
        self::assertRegExp('/[[!@#\$%\^&*\(\)_+-=\[\]\{\}<>]]/', $pass);
    }
}
