<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration;
use App\Model\AddressData;
interface AddressPort{ public function persist(AddressData $address): void; }
