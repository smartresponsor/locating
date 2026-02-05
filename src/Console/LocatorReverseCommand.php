<?php
declare(strict_types=1);
namespace Smartresponsor\Console;
use Smartresponsor\Service\Locator\LocatorService;
use Smartresponsor\Model\Locator\GeoPoint;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
#[AsCommand(name: 'locator:reverse', description: 'Reverse geocode a point')]
final class LocatorReverseCommand extends Command{
  public function __construct(private LocatorService $svc){ parent::__construct(); }
  protected function configure(): void{ $this->addArgument('lat', InputArgument::REQUIRED); $this->addArgument('lon', InputArgument::REQUIRED); }
  protected function execute(InputInterface $input, OutputInterface $output): int{
    $lat=(float)$input->getArgument('lat'); $lon=(float)$input->getArgument('lon');
    $a=$this->svc->reverse(new GeoPoint($lat,$lon))->toArray();
    $output->writeln(json_encode($a, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    return Command::SUCCESS;
  }
}
