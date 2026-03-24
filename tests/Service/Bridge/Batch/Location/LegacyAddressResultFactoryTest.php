<?php

declare(strict_types=1);

namespace Tests\Service\Bridge\Batch\Location;

use App\Entity\Location\AddressIssue;
use App\Entity\Location\AddressPipelineResult;
use App\Entity\Location\AddressView;
use App\InfrastructureInterface\Batch\Location\AddressBatchLegacyResultBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultRecordInterface;
use App\Service\Bridge\Batch\Location\LegacyAddressResultFactory;
use PHPUnit\Framework\TestCase;

final class LegacyAddressResultFactoryTest extends TestCase
{
    public function testCreateDelegatesToBackend(): void
    {
        $record = new class implements AddressBatchResultRecordInterface {
            public function toArray(): array
            {
                return ['ok' => true];
            }
        };

        $backend = new class($record) implements AddressBatchLegacyResultBackendInterface {
            public array $received = [];

            public function __construct(private AddressBatchResultRecordInterface $record)
            {
            }

            public function create(string $status, ?array $address, array $issues): AddressBatchResultRecordInterface
            {
                $this->received = [$status, $address, $issues];

                return $this->record;
            }
        };

        $factory = new LegacyAddressResultFactory($backend);
        $result = new AddressPipelineResult(
            'accepted',
            new AddressView('1 Main', 'Houston', 'TX', '77001', 'US'),
            [new AddressIssue('street', 'normalized', 'ok')],
        );

        self::assertSame($record, $factory->create($result));
        self::assertSame('accepted', $backend->received[0]);
        self::assertSame('1 Main', $backend->received[1]['street']);
        self::assertSame('normalized', $backend->received[2][0]['code']);
    }
}
