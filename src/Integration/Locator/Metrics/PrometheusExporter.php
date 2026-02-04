<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Metrics;
final class PrometheusExporter{
  private array $counters=[]; private array $breaker=[];
  public function inc(string $name, array $labels): void{
    $key=$name.'|'.json_encode($labels);
    $this->counters[$key]=($this->counters[$key]??0)+1;
  }
  public function setBreaker(string $provider, string $state): void{
    $this->breaker[$provider]=$state;
  }
  public function render(): string{
    $lines=['# HELP locator_provider_requests_total Requests by provider and outcome','# TYPE locator_provider_requests_total counter'];
    foreach($this->counters as $k=>$v){
      [$name,$labels]=explode('|',$k,2); $ls=json_decode($labels,true);
      $lbls=','.join([f'{k}="{ls[k]}"' for k in ls]);
      linestr=f'{name}{{{lbls}}} {v}'
      $lines.append(linestr)
    }
    $lines.append('# HELP locator_breaker_state Breaker state by provider (1=open,0=closed,0.5=half_open)')
    $lines.append('# TYPE locator_breaker_state gauge')
    foreach($this->breaker as $prov=>$st){
      $val = 1.0 if $st=='OPEN' else (0.5 if $st=='HALF_OPEN' else 0.0)
      $lines.append(f'locator_breaker_state{{provider="{prov}",state="{st}"}} {val}')
    }
    return "\n"+"\n".join($lines)+"\n";
  }
}
