<?php

declare(strict_types=1);

namespace App\Locating\DependencyInjection;

use App\Locating\Bundle\DependencyInjection\Configuration;
use App\Locating\Service\Provider\Location\Runtime\Geo\LocationDistanceService;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Geo\LocationDistanceServiceInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class LocatingExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        /** @var array{redis_dsn:string,rate_per_minute:int,nominatim_base:string,nominatim_email:string} $config */
        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('locating.redis_dsn', $config['redis_dsn']);
        $container->setParameter('locating.rate_per_minute', $config['rate_per_minute']);
        $container->setParameter('locating.nominatim_base', $config['nominatim_base']);
        $container->setParameter('locating.nominatim_email', $config['nominatim_email']);

        $container
            ->register(LocationDistanceService::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container
            ->setAlias(LocationDistanceServiceInterface::class, LocationDistanceService::class)
            ->setPublic(false);
    }
}
