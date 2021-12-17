<?php

declare(strict_types=1);

namespace AlexTartanTest\Helpers\TestAssets;

class TestPrivateParent
{
    private int $prop = 155;

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function method(): int
    {
        return $this->prop;
    }
}
