<?php

declare(strict_types=1);

use App\Locating\LocatingBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;

return [
    FrameworkBundle::class => ['all' => true],
    LocatingBundle::class => ['all' => true],
];
