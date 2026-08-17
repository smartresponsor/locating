<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\Model\Location\AddressView;
use App\Locating\ModelInterface\Location\AddressInputInterface;
use App\Locating\ModelInterface\Location\AddressViewInterface;
use App\Locating\ServiceInterface\Address\Location\AddressParserInterface;

final class AddressParser implements AddressParserInterface
{
    public function parse(AddressInputInterface $input): AddressViewInterface
    {
        $data = $input->data();

        if ([] !== $data) {
            return AddressView::fromArray($data);
        }

        $raw = trim($input->raw());
        if ('' === $raw) {
            return new AddressView('', '', '', '', '');
        }

        $parts = array_map('trim', explode(',', $raw));

        return new AddressView(
            $parts[0],
            $parts[1] ?? '',
            $parts[2] ?? '',
            $parts[3] ?? '',
            strtoupper($parts[4] ?? ''),
        );
    }
}
