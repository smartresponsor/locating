<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Health;
use SmartResponsor\Contract\Health\HealthCheckInterface;
use SmartResponsor\Integration\Locator\Http\NominatimClient;
final class NominatimHealthCheck implements HealthCheckInterface{
  public function __construct(private NominatimClient $c){}
  public function name(): string{ return 'nominatim'; }
  public function check(): array{
    $ok = $this->c->ping();
    return ['status'=> $ok? 'UP' : 'DOWN'];
  }
}
