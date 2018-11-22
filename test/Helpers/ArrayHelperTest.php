<?php
declare(strict_types=1);

namespace AlexTartanTest\Helpers;

use AlexTartan\Helpers\ArrayHelper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \AlexTartan\Helpers\ArrayHelper
 */
final class ArrayHelperTest extends TestCase
{
    public function testIndexObjectArrayByMethod()
    {
        $a = $this->getTestObject('a');
        $b = $this->getTestObject('b');
        $c = $this->getTestObject('c');

        $array = [$a, $b, $c];

        self::assertSame(
            ['a' => $a, 'b' => $b, 'c' => $c],
            ArrayHelper::indexObjectArrayByMethod($array, 'getProp')
        );
    }

    public function testIndexObjectArrayByMethodFailsOnNonExistingMethod()
    {
        $a = $this->getTestObject('a');
        $b = $this->getTestObject('b');
        $c = $this->getTestObject('c');

        $array = [$a, $b, $c];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Object does not implement 'getPropASD' method");

        self::assertSame(
            ['a' => $a, 'b' => $b, 'c' => $c],
            ArrayHelper::indexObjectArrayByMethod($array, 'getPropASD')
        );
    }

    public function testIndexObjectArrayByMethodFailsOnNonStringOrIntKeys()
    {
        $a = $this->getTestObject(new stdClass());
        $b = $this->getTestObject('b');
        $c = $this->getTestObject('c');

        $array = [$a, $b, $c];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key is neither a string nor an integer');

        self::assertSame(
            ['1' => $a, 'b' => $b, 'c' => $c],
            ArrayHelper::indexObjectArrayByMethod($array, 'getProp')
        );
    }

    public function testIndexObjectArrayByMethodFailsOnDuplicatesByDefault()
    {
        $a = $this->getTestObject('a');
        $b = $this->getTestObject('a');
        $c = $this->getTestObject('b');

        $array = [$a, $b, $c];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Duplicate key found: a');

        ArrayHelper::indexObjectArrayByMethod($array, 'getProp');
    }

    public function testIndexObjectArrayByMethodCanWorkWithDuplicates()
    {
        $a = $this->getTestObject('a');
        $b = $this->getTestObject('a');
        $c = $this->getTestObject('b');

        $array = [$a, $b, $c];

        self::assertSame(
            ['a' => $b, 'b' => $c,],
            ArrayHelper::indexObjectArrayByMethod($array, 'getProp', false)
        );
    }

    public function testIndexArrayOfArraysByKey()
    {
        $array = [
            ['test' => 14],
            ['test' => 24],
            ['test' => 34],
        ];

        self::assertSame(
            [
                14 => ['test' => 14],
                24 => ['test' => 24],
                34 => ['test' => 34],
            ],
            ArrayHelper::indexArrayOfArraysByKey($array, 'test')
        );
    }

    public function testIndexArrayOfArraysByKeyFailsOnNoKey()
    {
        $array = [
            ['test' => 14],
            ['testx' => 24],
            ['test' => 34],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Array does not have 'test' key");

        ArrayHelper::indexArrayOfArraysByKey($array, 'test');
    }

    public function testIndexArrayOfArraysByKeyFailsOnNonStringOrIntKeys()
    {
        $array = [
            ['test' => 14],
            ['test' => new stdClass()],
            ['test' => 34],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key is neither a string nor an integer');

        ArrayHelper::indexArrayOfArraysByKey($array, 'test');
    }

    public function testIndexArrayOfArraysByKeyFailsOnDuplicatesByDefault()
    {
        $array = [
            ['test' => 14],
            ['test' => 14],
            ['test' => 34],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Duplicate key found: 14');

        ArrayHelper::indexArrayOfArraysByKey($array, 'test');
    }

    public function testIndexArrayOfArraysByKeyCanWorkWithDuplicates()
    {
        $array = [
            ['test' => 14],
            ['test' => 14],
            ['test' => 34],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Duplicate key found: 14');

        self::assertSame(
            [
                14 => ['test' => 14],
                34 => ['test' => 34],
            ],
            ArrayHelper::indexArrayOfArraysByKey($array, 'test')
        );
    }

    private function getTestObject($param)
    {
        return new class($param)
        {
            private $prop;

            public function __construct($prop)
            {
                $this->prop = $prop;
            }

            public function getProp()
            {
                return $this->prop;
            }
        };
    }
}
