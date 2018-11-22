<?php
declare(strict_types=1);

namespace AlexTartanTest\Helpers;

use AlexTartan\Helpers\EmailAddressHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AlexTartan\Helpers\EmailAddressHelper
 */
final class EmailAddressHelperTest extends TestCase
{
    public function testCanConstruct()
    {
        $helper = new EmailAddressHelper('valid@google.com');

        self::assertInstanceOf(EmailAddressHelper::class, $helper);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The provided value is not a valid email address
     */
    public function testFailWithExceptionOnInvalidEmailAddress()
    {
        new EmailAddressHelper('google.com');
    }

    public function testGetDomain()
    {
        $helper = new EmailAddressHelper('valid@google.com');
        self::assertEquals(
            'google.com',
            $helper->getDomain()
        );
    }

    public function testGetUsername()
    {
        $helper = new EmailAddressHelper('valid@google.com');
        self::assertEquals(
            'valid',
            $helper->getUsername()
        );
    }
}
