<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Strategy;
use Smartresponsor\Contract\LocatorInterface;
use Smartresponsor\Model\AddressData;
use Smartresponsor\Model\GeoPoint;
use Smartresponsor\Integration\Http\USPSClient;
final class USPSLocator implements LocatorInterface{
    public function __construct(private readonly USPSClient $client){}
    public function normalize(string $rawAddress): AddressData{
        $parts=array_map('trim',explode(',',$rawAddress)); $street=$parts[0]??''; $city=$parts[1]??''; $state=$parts[2]??''; $zip=$parts[3]??'';
        $zip5=''; $zip4=''; if(strpos($zip,'-')!==false){[$zip5,$zip4]=array_map('trim',explode('-',$zip,2));} else { $zip5=$zip; }
        $v=$this->client->verify($street,$city,$state,$zip5,$zip4);
        return new AddressData((string)($v['street']??''),(string)($v['city']??''),(string)($v['state']??''),(string)(($v['zip5']??'').((isset($v['zip4'])&&$v['zip4']!=='')?'-'.$v['zip4']:'')),'US');
    }
    public function geocode(AddressData $a): GeoPoint{ return new GeoPoint(0.0,0.0); }
    public function reverse(GeoPoint $p): AddressData{ return new AddressData('','','','','US'); }
}
