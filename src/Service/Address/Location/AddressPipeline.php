<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\ModelInterface\Location\AddressInputInterface;
use App\Locating\ModelInterface\Location\AddressPipelineResultInterface;
use App\Locating\ServiceInterface\Address\Location\AddressNormalizerInterface;
use App\Locating\ServiceInterface\Address\Location\AddressParserInterface;
use App\Locating\ServiceInterface\Address\Location\AddressPipelineInterface;
use App\Locating\ServiceInterface\Address\Location\AddressValidatorInterface;

final class AddressPipeline implements AddressPipelineInterface
{
    public function __construct(
        private readonly AddressParserInterface $parser,
        private readonly AddressNormalizerInterface $normalizer,
        private readonly AddressValidatorInterface $validator,
    ) {
    }

    public function process(AddressInputInterface $input): AddressPipelineResultInterface
    {
        $parsed = $this->parser->parse($input);
        $normalized = $this->normalizer->normalize($parsed);

        return $this->validator->validate($normalized);
    }
}
