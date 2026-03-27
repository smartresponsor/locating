<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Address\Location;

use App\EntityInterface\Location\AddressInputInterface;
use App\EntityInterface\Location\AddressPipelineResultInterface;
use App\ServiceInterface\Address\Location\AddressNormalizerInterface;
use App\ServiceInterface\Address\Location\AddressParserInterface;
use App\ServiceInterface\Address\Location\AddressPipelineInterface;
use App\ServiceInterface\Address\Location\AddressValidatorInterface;

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
