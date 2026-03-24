<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Integration;
use Smartresponsor\Model\AddressData;
interface AddressPort{ public function persist(AddressData $address): void; }
