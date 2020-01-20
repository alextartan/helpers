<?php

declare(strict_types=1);

namespace AlexTartanTest\Helpers\TestAssets;

class TestPrivateParent
{
    private int $prop = 155;

    private function method(): int
    {
        return $this->prop;
    }
}
