<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Contract\Log;
interface LoggerInterface{
  /** @param array<string,mixed> $context */
  public function log(string $level, string $message, array $context=[]): void;
  /** @param array<string,mixed> $context */
  public function info(string $message, array $context=[]): void;
  /** @param array<string,mixed> $context */
  public function error(string $message, array $context=[]): void;
}
