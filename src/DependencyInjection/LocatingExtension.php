<?php

declare(strict_types=1);

namespace App\Locating\DependencyInjection;

use App\Locating\Service\Provider\Location\Runtime\Geo\LocationDistanceService;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Geo\LocationDistanceServiceInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class LocatingExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container
            ->register(LocationDistanceService::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container
            ->setAlias(LocationDistanceServiceInterface::class, LocationDistanceService::class)
            ->setPublic(false);
    }
}
