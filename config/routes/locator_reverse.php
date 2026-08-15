<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->add('locator_address_reverse', '/location/address/reverse')
        ->controller('App\Locating\\Controller\\Http\\Location\\AddressReverseController')
        ->methods(['GET']);
};
