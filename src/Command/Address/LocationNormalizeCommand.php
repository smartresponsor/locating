<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Command\Address;

use App\Entity\Location\AddressInput;
use App\ServiceInterface\Address\Location\AddressPipelineInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'location:normalize', description: 'Normalize a raw address into the canonical location payload.')]
final class LocationNormalizeCommand extends Command
{
    public function __construct(private readonly AddressPipelineInterface $addressPipeline)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('raw', InputArgument::REQUIRED, 'Raw address string.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $raw = (string) $input->getArgument('raw');
        $result = $this->addressPipeline->process(new AddressInput($raw));

        $output->writeln(json_encode($result->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        return Command::SUCCESS;
    }
}
