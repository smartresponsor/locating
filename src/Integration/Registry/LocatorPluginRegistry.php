<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Integration\Registry;
use Smartresponsor\Contract\Provider\LocatorProviderInterface;
final class LocatorPluginRegistry{
  /** @var list<LocatorProviderInterface> */
  private array $providers = [];
  public function register(LocatorProviderInterface $p): void{ $this->providers[] = $p; }
  /** @return list<LocatorProviderInterface> */
  public function all(): array{ return $this->providers; }
  public function healthiest(): ?LocatorProviderInterface{
    $candidates = array_values(array_filter($this->providers, fn($p)=> $p->isHealthy()));
    if ($candidates===[]) return null;
    usort($candidates, fn($a,$b)=> $b->getPriority() <=> $a->getPriority());
    return $candidates[0];
  }
}
