<?php

namespace AlexTartan\Helpers\Stream;

use const STREAM_REPORT_ERRORS;

interface Stream
{
    public function stream_open(string $path, string $mode, int $options = STREAM_REPORT_ERRORS, string &$opened_path = null): bool;

    public function stream_read(int $count): string;

    public function stream_eof(): bool;

    public function stream_close(): void;
}
