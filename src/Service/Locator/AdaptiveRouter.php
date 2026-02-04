<?php
declare(strict_types=1);
namespace SmartResponsor\Service\Locator;
final class AdaptiveRouter{
  public function __construct(private HealthMonitor $monitor, private int $minScore=-800){}
  public function order(array $providers): array{
    $snap=$this->monitor->snapshot();
    usort($providers, function($a,$b) use ($snap){
      $sa=$snap[$a->name()]??['score'=>0]; $sb=$snap[$b->name()]??['score'=>0];
      return ($sb['score']??0) <=> ($sa['score']??0);
    });
    $filtered=[]; foreach($providers as $p){ $s=$snap[$p->name()]??['score'=>0]; if(($s['score']??0) >= $this->minScore){ $filtered[]=$p; } }
    return $filtered;
  }
}
