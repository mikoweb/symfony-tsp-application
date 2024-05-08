<?php

namespace App\Module\TSP\UI\CLI;

use App\Core\Application\Path\AppPathResolver;
use App\Module\TSP\Application\Optimal\Event\FindOptimalParametersStartedEvent;
use App\Module\TSP\Application\Optimal\Event\OptimalParametersFoundEvent;
use App\Module\TSP\Application\Optimal\Event\ParametersTestedEvent;
use App\Module\TSP\Application\Optimal\OptimalParametersFinder;
use App\Shared\Domain\Location;
use App\Shared\Domain\LocationCollection;
use MathPHP\Exception\BadDataException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcher;

use function Symfony\Component\String\u;

#[AsCommand(
    name: 'app:tsp:find-optimal-parameters',
    description: 'Find optimal parameters for TSP',
)]
class TspFindOptimalParametersCommand extends Command
{
    private ProgressBar $progressBar;

    public function __construct(
        private readonly OptimalParametersFinder $optimalParametersFinder,
        private readonly AppPathResolver $appPathResolver,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('iterations', InputArgument::REQUIRED, 'Iterations value')
            ->addArgument('test_attempts_num', InputArgument::REQUIRED, 'How many times to test properties')
            ->addOption('locations_json_file', null, InputOption::VALUE_OPTIONAL, 'JSON file with locations')
        ;
    }

    /**
     * @throws BadDataException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $locationsPath = empty($input->getOption('locations_json_file'))
            ? $this->appPathResolver->getFixturesPath('sample_locations.json')
            : $this->appPathResolver->getAppPath($input->getOption('locations_json_file'));

        $iterations = $input->getArgument('iterations');
        $testAttemptsNum = $input->getArgument('test_attempts_num');

        $io->info('Generating a test set...');

        $locations = $this->loadLocations($locationsPath);
        $eventDispatcher = new EventDispatcher();

        ProgressBar::setFormatDefinition(
            'optimal_parameters',
            '%current%/%max% of Properties [%bar%] %percent:3s%%'
        );

        $eventDispatcher->addListener(
            FindOptimalParametersStartedEvent::class,
            function (FindOptimalParametersStartedEvent $event) use ($output, $io) {
                $io->info('The find has begun!');
                $this->progressBar = new ProgressBar($output, $event->parametersToTestNumber);
                $this->progressBar->setFormat('optimal_parameters');
                $this->progressBar->start();
            }
        );

        $eventDispatcher->addListener(ParametersTestedEvent::class, fn () => $this->progressBar->advance());
        $eventDispatcher->addListener(OptimalParametersFoundEvent::class, fn () => $this->progressBar->finish());

        $outputFile = $this->appPathResolver->getAppPath(
            u(date('Y-m-d-H-i-s'))
                ->append('_')
                ->append((string) $iterations)
                ->append('_')
                ->append((string) $testAttemptsNum)
                ->append('_optimal_parameters_results.json')
        );

        file_put_contents($outputFile, json_encode(
            $this->optimalParametersFinder->find($locations, $iterations, $testAttemptsNum, $eventDispatcher)
                ->toArray(),
            JSON_PRETTY_PRINT
        ));

        $io->writeln('');
        $io->writeln('');
        $io->success(sprintf('Generated results in file: %s', $outputFile));

        return Command::SUCCESS;
    }

    private function loadLocations(string $locationsPath): LocationCollection
    {
        $json = json_decode(file_get_contents($locationsPath));

        return new LocationCollection(array_map(
            fn (object $item) => new Location($item->lat, $item->lng, $item->name),
            $json
        ));
    }
}
