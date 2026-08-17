<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Metrics\Log;

use App\Locating\Contract\Location\LocationLoggerContract as LoggerInterface;

final class StdoutLogger implements LoggerInterface
{
    public function log(string $level, string $message, array $context = []): void
    {
        $row = ['ts' => date('c'), 'level' => $level, 'msg' => $message, 'ctx' => $context];
        fwrite(STDERR, json_encode($row, JSON_UNESCAPED_UNICODE).PHP_EOL);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }
}
