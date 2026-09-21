<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Batch\Location;

use App\Locating\Model\Location\AddressIssue;
use App\Locating\Model\Location\AddressPipelineResult;
use App\Locating\Model\Location\AddressView;
use App\Locating\ModelInterface\Location\AddressBatchResultRecordInterface;
use App\Locating\Service\Batch\Location\AddressResultFactory;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchResultBackendInterface;
use PHPUnit\Framework\TestCase;

final class AddressResultFactoryTest extends TestCase
{
    public function testCreateDelegatesToBackend(): void
    {
        $record = new class () implements AddressBatchResultRecordInterface {
            public function toArray(): array
            {
                return ['ok' => true];
            }
        };

        $backend = new class ($record) implements AddressBatchResultBackendInterface {
            /** @var array{0:string,1:?array<string,mixed>,2:list<array<string,mixed>>} */
            public array $received = ['', null, []];

            public function __construct(private AddressBatchResultRecordInterface $record)
            {
            }

            public function create(string $status, ?array $address, array $issues): AddressBatchResultRecordInterface
            {
                $this->received = [$status, $address, $issues];

                return $this->record;
            }
        };

        $factory = new AddressResultFactory($backend);
        $result = new AddressPipelineResult(
            'accepted',
            new AddressView('1 Main', 'Houston', 'TX', '77001', 'US'),
            [new AddressIssue('street', 'normalized', 'ok')],
        );

        self::assertSame($record, $factory->create($result));
        self::assertSame('accepted', $backend->received[0]);
        $receivedAddress = $backend->received[1];
        self::assertNotNull($receivedAddress);
        self::assertSame('1 Main', $receivedAddress['street']);
        self::assertSame('normalized', $backend->received[2][0]['code']);
    }
}
