<?php

declare(strict_types=1);

namespace AlexTartan\Helpers;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

final class DateHelper
{
    public static function getDateTimeFromDateTimeImmutable(DateTimeImmutable $dateTimeImmutable): DateTime
    {
        $dateTime = new DateTime('', $dateTimeImmutable->getTimezone());
        $dateTime->setTimestamp($dateTimeImmutable->getTimestamp());

        return $dateTime;
    }

    public static function toYmdHis(DateTimeInterface $dateTime = null): string
    {
        return $dateTime !== null ? $dateTime->format('Y-m-d H:i:s') : '';
    }
}
