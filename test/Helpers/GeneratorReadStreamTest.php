<?php

declare(strict_types=1);

namespace AlexTartanTest\Helpers;

use AlexTartan\Helpers\Stream\GeneratorReadStream;
use AlexTartan\Helpers\Stream\StreamException;
use Exception;
use Generator;
use PHPUnit\Framework\TestCase;

use function fclose;
use function feof;
use function fopen;
use function fread;
use function fstat;
use function random_bytes;
use function strlen;

/**
 * @covers \AlexTartan\Helpers\Stream\GeneratorReadStream
 */
final class GeneratorReadStreamTest extends TestCase
{
    public function getGeneratorParams(): array
    {
        $readSizes = [
            1024,
            2048,
            4096,
            8192,
        ];

        $chunkSizes = [
            1000,
            1024,
            2000,
            2048,
            4000,
            4096,
            8000,
            8192,
            16384,
        ];

        $return = [];
        for ($i = 1; $i < 100; $i++) {
            foreach ($chunkSizes as $j) {
                foreach ($readSizes as $k) {
                    $return[] = [
                        $i,
                        $j,
                        $k,
                    ];
                }
            }
        }

        return $return;
    }

    /** @throws Exception */
    private function getGenerator(int $numberOfIterations, int $chunkSize): Generator
    {
        for ($i = 0; $i < $numberOfIterations; $i++) {
            yield random_bytes($chunkSize);
        }
    }

    /**
     * @dataProvider getGeneratorParams
     * @throws Exception
     */
    public function testSomething(int $numberOfIterations, int $chunkSize, int $readLength): void
    {
        $id = GeneratorReadStream::createResourceUrl(
            $this->getGenerator($numberOfIterations, $chunkSize)
        );
        $fp = fopen('generator://' . $id, 'rb');
        if ($fp === false) {
            self::fail('cannot read stream');
        }

        $s = '';
        while (!feof($fp)) {
            $res = fread($fp, $readLength);
            $s   .= $res;
        }
        fclose($fp);

        self::assertSame(strlen($s), $numberOfIterations * $chunkSize);
    }

    /**
     * @dataProvider invalidOpenModes
     *
     * @throws Exception
     */
    public function testCannotOpenInWriteMode(string $mode): void
    {
        $this->expectException(StreamException::class);
        $this->expectExceptionMessage('This stream is readonly');

        $id = GeneratorReadStream::createResourceUrl(
            $this->getGenerator(1, 2)
        );
        fopen('generator://' . $id, $mode);
    }

    public function invalidOpenModes(): array
    {
        $modes        = ['r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+', 'e'];
        $specialFlags = ['b', 't'];

        $return = [];
        foreach ($modes as $mode) {
            foreach ($specialFlags as $flag) {
                $return[] = [$mode . $flag];
            }
        }

        return $return;
    }

    /** @throws Exception */
    public function testStat(): void
    {
        $id = GeneratorReadStream::createResourceUrl(
            $this->getGenerator(1, 2)
        );
        $fp = fopen('generator://' . $id, 'rb');
        if ($fp === false) {
            self::fail('cannot read stream');
        }

        self::assertSame(
            [
                0         => 0,
                1         => 0,
                2         => 33060, // rb
                3         => 0,
                4         => 0,
                5         => 0,
                6         => 0,
                7         => 1,
                8         => 0,
                9         => 0,
                10        => 0,
                11        => 0,
                12        => 0,
                'dev'     => 0,
                'ino'     => 0,
                'mode'    => 33060, // rb
                'nlink'   => 0,
                'uid'     => 0,
                'gid'     => 0,
                'rdev'    => 0,
                'size'    => 1,
                'atime'   => 0,
                'mtime'   => 0,
                'ctime'   => 0,
                'blksize' => 0,
                'blocks'  => 0,
            ],
            fstat($fp)
        );
    }
}
