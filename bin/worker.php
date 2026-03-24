<?php
declare(strict_types=1);
require __DIR__.'/../vendor/autoload.php';
use App\Integration\Batch\JobStore;
use App\Integration\Provider\MockProvider;
use App\Integration\Provider\NominatimProvider;
use App\Service\Aggregator;

$store=new JobStore();
$order = array_values(array_filter(array_map('trim', explode(',', getenv('LOCATOR_PROVIDERS') ?: 'mock,nominatim'))));
$providers=[];
foreach($order as $name){ if($name==='mock'){ $providers[] = new MockProvider(); } elseif($name==='nominatim'){ $providers[] = new NominatimProvider(getenv('NOMINATIM_URL') ?: 'https://nominatim.openstreetmap.org'); } }
$agg = new Aggregator($providers);

$poll = (int)(getenv('LOCATOR_WORKER_POLL') ?: 2);
echo "Worker started. Poll interval: {$poll}s\n";
while(true){
  $ids = $store->listByStatus('created');
  foreach($ids as $id){
    $job=$store->read($id); if(!$job) continue;
    $job['status']='processing'; $job['started_at']=date('c'); $store->write($job);
    foreach($job['items'] as $i=>$it){
      try{
        $r=$agg->locate((string)$it['q']);
        $job['items'][$i] = ['q'=>$it['q'],'ok'=>true,'lat'=>$r['lat'],'lon'=>$r['lon'],'provider'=>$r['provider'],'formatted'=>$r['formatted']];
      }catch(Throwable $e){
        $job['items'][$i] = ['q'=>$it['q'],'ok'=>false,'error'=>$e->getMessage()];
      }
      $job['done'] = $i+1; $store->write($job);
    }
    $job['status']='done'; $job['finished_at']=date('c'); $store->write($job);
    echo "Job {$id} done\n";
  }
  sleep($poll);
}
