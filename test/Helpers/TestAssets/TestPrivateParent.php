<?php

declare(strict_types=1);

namespace AlexTartanTest\Helpers\TestAssets;

class TestPrivateParent
{
    /** @var int */
    private $prop = 155;

    private function method(): int
    {
        return $this->prop;
    }
}
