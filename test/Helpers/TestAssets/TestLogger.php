<?php
declare(strict_types=1);

namespace AlexTartanTest\Helpers\TestAssets;

use Psr\Log\AbstractLogger;
use const PHP_EOL;

class TestLogger extends AbstractLogger
{
    public function log($level, $message, array $context = []): void
    {
        echo $message . PHP_EOL . print_r($message, true) . PHP_EOL;
    }
}
