<?php

declare(strict_types=1);

namespace AlexTartanTest\Helpers;

use AlexTartan\Helpers\ReflectionHelper;
use AlexTartanTest\Helpers\TestAssets\TestPrivateChild;
use AlexTartanTest\Helpers\TestAssets\TestPrivateParent;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AlexTartan\Helpers\ReflectionHelper
 */
final class ReflectionHelperTest extends TestCase
{
    public function testCallPrivateMethod(): void
    {
        $x = new class () {
            /** @noinspection PhpUnusedPrivateMethodInspection */
            private function privateMethod(): int
            {
                return 123;
            }
        };

        self::assertSame(
            123,
            ReflectionHelper::callPrivateMethod($x, 'privateMethod', [])
        );
    }

    public function testSetPrivatePropertyValue(): void
    {
        $x = new class () {
            /** @var int */
            private $privateProperty;

            public function getVal(): int
            {
                return $this->privateProperty;
            }
        };

        $value = 123;
        ReflectionHelper::setPrivatePropertyValue($x, 'privateProperty', $value);

        self::assertSame(
            $value,
            $x->getVal()
        );
    }

    public function testGetPrivatePropertyValueFromParentClass(): void
    {
        $x = new TestPrivateChild();

        $value = ReflectionHelper::getPrivatePropertyValue($x, 'prop', TestPrivateParent::class);

        self::assertSame(
            $value,
            155
        );
    }

    public function testSetPrivatePropertyValueFromParentClass(): void
    {
        $x = new TestPrivateChild();

        ReflectionHelper::setPrivatePropertyValue($x, 'prop', 255, TestPrivateParent::class);

        self::assertSame(
            ReflectionHelper::getPrivatePropertyValue($x, 'prop', TestPrivateParent::class),
            255
        );
    }

    public function testGetPrivatePropertyValue(): void
    {
        $x = new class () {
            /** @var int */
            private /** @noinspection PhpUnusedPrivateFieldInspection */ $privateProperty = 123;
        };

        self::assertSame(
            123,
            ReflectionHelper::getPrivatePropertyValue($x, 'privateProperty')
        );
    }


    public function testGetPrivateConstant(): void
    {
        $x = new class () {
            private const /** @noinspection PhpUnusedPrivateFieldInspection */ TEST = 'testing';
        };

        self::assertSame(
            'testing',
            ReflectionHelper::getConstant($x, 'TEST')
        );
    }
}
