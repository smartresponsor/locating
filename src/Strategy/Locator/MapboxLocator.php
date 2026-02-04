<?php
declare(strict_types=1);
namespace SmartResponsor\Strategy\Locator;
use SmartResponsor\Contract\Locator\LocatorInterface;
use SmartResponsor\Integration\Locator\Http\MapboxClient;
use SmartResponsor\Model\Locator\AddressData;
use SmartResponsor\Model\Locator\GeoPoint;
final class MapboxLocator implements LocatorInterface{
  public function __construct(private MapboxClient $c){}
  public function normalize(string $raw): AddressData{
    $d=$this->c->geocode($raw); $f=$d['features'][0]??[]; $c=$f['context']??[];
    $city=''; $region=''; $pc=''; $cc='';
    foreach($c as $it){ $id=(string)($it['id']??''); $txt=(string)($it['text']??''); if(str_starts_with($id,'place.')) $city=$txt; if(str_starts_with($id,'region.')) $region=$txt; if(str_starts_with($id,'postcode.')) $pc=$txt; if(str_starts_with($id,'country.')) $cc=strtoupper($txt); }
    $street=(string)($f['text']??'');
    return new AddressData($street,$city,$region,$pc,$cc);
  }
  public function geocode(AddressData $a): GeoPoint{
    $d=$this->c->geocode($a->oneLine()); $f=$d['features'][0]??[]; $p=$f['center']??[0,0]; return new GeoPoint((float)($p[1]??0),(float)($p[0]??0));
  }
  public function reverse(GeoPoint $p): AddressData{
    $d=$this->c->reverse($p->latitude,$p->longitude); $f=$d['features'][0]??[]; $c=$f['context']??[];
    $city=''; $region=''; $pc=''; $cc=''; foreach($c as $it){ $id=(string)($it['id']??''); $txt=(string)($it['text']??''); if(str_starts_with($id,'place.')) $city=$txt; if(str_starts_with($id,'region.')) $region=$txt; if(str_starts_with($id,'postcode.')) $pc=$txt; if(str_starts_with($id,'country.')) $cc=strtoupper($txt); }
    $street=(string)($f['text']??'');
    return new AddressData($street,$city,$region,$pc,$cc);
  }
}
