<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Integration\Health;
use Smartresponsor\Contract\Health\HealthCheckInterface;
use Smartresponsor\Integration\Http\NominatimClient;
final class NominatimHealthCheck implements HealthCheckInterface{
  public function __construct(private NominatimClient $c){}
  public function name(): string{ return 'nominatim'; }
  public function check(): array{
    $ok = $this->c->ping();
    return ['status'=> $ok? 'UP' : 'DOWN'];
  }
}
