<?php

declare(strict_types=1);

namespace App\Locating\Integration\Observability\Log;

final class JsonLogger
{
    private ?string $file;
    public function __construct(private string $service = 'locator', string $target = 'stdout')
    {
        $this->file = ($target === 'stdout') ? null : $target;
        if ($this->file) {
            @mkdir(dirname($this->file), 0755, true);
        }
    }
    private function emit(array $rec): void
    {
        $rec['ts'] = date('c');
        $rec['service'] = $this->service;
        $line = json_encode($rec, JSON_UNESCAPED_UNICODE);
        if ($this->file) {
            file_put_contents($this->file, $line."\n", FILE_APPEND);
        } else {
            error_log($line);
        }
    }
    public function info(string $msg, array $ctx = []): void
    {
        $this->emit(['level' => 'INFO','msg' => $msg,'ctx' => $ctx]);
    }
    public function warn(string $msg, array $ctx = []): void
    {
        $this->emit(['level' => 'WARN','msg' => $msg,'ctx' => $ctx]);
    }
    public function error(string $msg, array $ctx = []): void
    {
        $this->emit(['level' => 'ERROR','msg' => $msg,'ctx' => $ctx]);
    }
}
