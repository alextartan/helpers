<?php

declare(strict_types=1);

namespace AlexTartanTest\Helpers;

use AlexTartan\Helpers\EmailAddressHelper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AlexTartan\Helpers\EmailAddressHelper
 */
final class EmailAddressHelperTest extends TestCase
{
    public function testFailWithExceptionOnInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The provided value is not a valid email address');

        new EmailAddressHelper('google.com');
    }

    public function testGetDomain(): void
    {
        $helper = new EmailAddressHelper('valid@google.com');
        self::assertEquals(
            'google.com',
            $helper->getDomain()
        );
    }

    public function testGetUsername(): void
    {
        $helper = new EmailAddressHelper('valid@google.com');
        self::assertEquals(
            'valid',
            $helper->getUsername()
        );
    }
}
