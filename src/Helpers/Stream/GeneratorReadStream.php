<?php
declare(strict_types=1);

namespace AlexTartan\Helpers\Stream;

use Generator;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use function in_array;
use function stream_context_get_default;
use function stream_context_get_options;
use function stream_context_set_default;
use function strlen;

class GeneratorReadStream implements Stream
{
    public const PROTOCOL = 'generator';

    /** @var string */
    private $path;

    /** @var string */
    private $buffer;

    /** @var Generator */
    private $generator;

    /** @var LoggerInterface */
    private $logger;

    public static function createResourceUrl(
        Generator $generator,
        LoggerInterface $logger = null
    ): string {
        if (in_array(self::PROTOCOL, stream_get_wrappers(), true)) {
            stream_wrapper_unregister(self::PROTOCOL);
        }
        stream_wrapper_register(self::PROTOCOL, static::class);

        // set defaults
        $default                              = stream_context_get_options(stream_context_get_default());
        $default[self::PROTOCOL]['generator'] = $generator;
        $default[self::PROTOCOL]['id']        = uniqid('', true);
        $default[self::PROTOCOL]['buffer']    = '';
        $default[self::PROTOCOL]['logger']    = $logger ?? new NullLogger();

        stream_context_set_default($default);

        return self::PROTOCOL . '://' . $default[self::PROTOCOL]['id'];
    }

    public function stream_open(string $path, string $mode, int $options = STREAM_REPORT_ERRORS, string &$opened_path = null): bool
    {
        if (!in_array($mode, ['r', 'rb'], true)) {
            throw new StreamException('This stream is readonly');
        }

        $this->initProtocol($path);

        return true;
    }

    public function stream_read(int $count): string
    {
        $this->logger->info("stream_read called asking for $count bytes");

        $out           = $this->buffer;
        $currentLength = strlen($this->buffer);

        while ($currentLength < $count && $this->generator->valid()) {
            $currentValue = $this->generator->current();
            $out          .= $currentValue;
            $this->generator->next();
            $currentLength += strlen($currentValue);
            $this->logger->info('loop read ' . strlen($currentValue) . ' bytes');
        }
        $this->logger->info("read $currentLength bytes");

        // grabbing the requested size from what has been read
        $returnValue = substr($out, 0, $count);

        // storing the rest of the read content into the buffer, so it gets picked up on the next iteration
        if (strlen($out) > $count) {
            $this->buffer = substr($out, $count);
        } else {
            $this->buffer = '';
        }

        $this->logger->info('buffer now contains ' . strlen($this->buffer));
        $this->logger->info('EOF?: ' . ($this->stream_eof() ? 'Y' : 'N'));

        return $returnValue;
    }

    public function stream_eof(): bool
    {
        return !$this->generator->valid() && $this->buffer === '';
    }

    public function stream_close(): void
    {
        $this->logger->info("stream closed. buffer is: $this->buffer");
    }

    /**
     * Parse the protocol out of the given path.
     */
    private function initProtocol(string $path): void
    {
        $parts           = explode('://', $path, 2);
        $this->path      = $parts[1];
        $options         = $this->getOptions()[self::PROTOCOL];
        $this->buffer    = $options['buffer'];
        $this->generator = $options['generator'];
        $this->logger    = $options['logger'];
    }

    private function getOptions(): array
    {
        return stream_context_get_options(stream_context_get_default());
    }
}
