<?php
declare(strict_types=1);

namespace AlexTartanTest\Helpers;

use AlexTartan\Helpers\DateHelper;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AlexTartanTest\Helpers\DateHelper
 */
final class DateHelperTest extends TestCase
{
    public function testGetDateTimeFromDateTimeImmutable()
    {
        $dti = new DateTimeImmutable();
        $dt  = DateHelper::getDateTimeFromDateTimeImmutable($dti);

        self::assertSame($dt->getTimestamp(), $dti->getTimestamp());
        self::assertSame($dt->getOffset(), $dti->getOffset());
        // "equals", not "same" because they're different objects
        self::assertEquals($dt->getTimezone(), $dti->getTimezone());
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testGetDateTimeFromDateTimeImmutableWithSpecificTimezone(
        string $date,
        string $timezone,
        int $timestamp
    ) {
        $dti = new DateTimeImmutable($date, new DateTimeZone($timezone));
        $dt  = DateHelper::getDateTimeFromDateTimeImmutable($dti);

        self::assertSame($dt->getTimestamp(), $dti->getTimestamp());
        self::assertSame($dt->getOffset(), $dti->getOffset());
        // "equals", not "same" because they're different objects
        self::assertEquals($dt->getTimezone(), $dti->getTimezone());

        self::assertSame($dt->getTimestamp(), $timestamp);
    }

    public function dateTimeProvider(): array
    {
        return [
            ['2018-11-26 23:59:59', 'EEST', 1543269599],
            ['2016-12-31 23:59:60', 'UTC', 1483228800],
            ['2016-02-29 12:59:00', 'UTC', 1456750740],
        ];
    }
}
