<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Command\Locator;

use Smartresponsor\CommandInterface\Locator\LocatorDemoSeedCommandInterface;
use Smartresponsor\ServiceInterface\Locator\LocatorDemoSeedInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class LocatorDemoSeedCommand extends Command implements LocatorDemoSeedCommandInterface
{
    protected static $defaultName = 'locator:demo:seed';

    public function __construct(private LocatorDemoSeedInterface $demoSeed)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Seed Locator demo pipeline from fixtures/locator-demo.ndjson')
            ->addOption('tenant', null, InputOption::VALUE_REQUIRED, 'Tenant identifier', 'tenant-demo')
            ->addOption('file', null, InputOption::VALUE_OPTIONAL, 'Path to NDJSON fixture file');
    }

    public function runSeed(string $tenantId, ?string $filePath = null): int
    {
        if ($filePath !== null && $filePath !== '') {
            return $this->demoSeed->seedFromFile($tenantId, $filePath);
        }

        return $this->demoSeed->seedDemo($tenantId);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tenantId = (string)$input->getOption('tenant');
        $filePath = $input->getOption('file');
        if (!is_string($filePath)) {
            $filePath = null;
        }

        $count = $this->runSeed($tenantId, $filePath);

        $output->writeln(sprintf(
            'Locator demo seed complete for tenant "%s", record count: %d',
            $tenantId,
            $count
        ));

        return Command::SUCCESS;
    }
}
