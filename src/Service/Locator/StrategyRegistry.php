<?php
declare(strict_types=1);
namespace SmartResponsor\Service\Locator;
use SmartResponsor\Contract\Locator\ProviderInterface;
use SmartResponsor\Contract\Locator\ReverseProviderInterface;
final class StrategyRegistry{
  private array $providers=[];
  public function add(object $p): void{ $this->providers[method_exists($p,'name')?$p->name():get_class($p)]=$p; }
  public function byName(string $name): ?object{ return $this->providers[$name]??null; }
  /** @return array<int,string> */ public function names(): array{ return array_keys($this->providers); }
}
