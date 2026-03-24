<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Command;

use Smartresponsor\CommandInterface\LocatorDemoLoopCommandInterface;
use Smartresponsor\ServiceInterface\LocatorDemoLoopInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class LocatorDemoLoopCommand extends Command implements LocatorDemoLoopCommandInterface
{
    protected static $defaultName = 'locator:demo:loop';

    public function __construct(private LocatorDemoLoopInterface $demoLoop)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Run Locator demo loop that drives the pipeline from demo fixtures')
            ->addOption('tenant', null, InputOption::VALUE_REQUIRED, 'Tenant identifier', 'tenant-demo')
            ->addOption('round', null, InputOption::VALUE_OPTIONAL, 'Round count', '10')
            ->addOption('sleep', null, InputOption::VALUE_OPTIONAL, 'Sleep time between rounds in seconds', '2');
    }

    public function runLoop(string $tenantId, int $roundMax, int $sleepSecond): int
    {
        $this->demoLoop->runLoop($tenantId, $roundMax, $sleepSecond);

        return 0;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tenantId = (string)$input->getOption('tenant');
        $roundRaw = (string)$input->getOption('round');
        $sleepRaw = (string)$input->getOption('sleep');

        $roundMax = (int)$roundRaw;
        $sleepSecond = (int)$sleepRaw;

        $output->writeln(sprintf(
            'Locator demo loop start for tenant "%s", round=%d, sleep=%d second',
            $tenantId,
            $roundMax,
            $sleepSecond
        ));

        $this->runLoop($tenantId, $roundMax, $sleepSecond);

        $output->writeln('Locator demo loop complete.');

        return Command::SUCCESS;
    }
}
