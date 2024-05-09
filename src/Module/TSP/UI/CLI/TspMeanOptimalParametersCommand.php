<?php

namespace App\Module\TSP\UI\CLI;

use App\Core\Application\Path\AppPathResolver;
use App\Module\TSP\Application\Optimal\VO\TestParametersVO;
use MathPHP\Exception\BadDataException;
use MathPHP\Statistics\Average;
use Ramsey\Collection\Collection;
use Ramsey\Collection\CollectionInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

#[AsCommand(
    name: 'app:tsp:mean-optimal-parameters',
    description: 'Get mean of optimal parameters',
)]
class TspMeanOptimalParametersCommand extends Command
{
    public function __construct(
        private readonly AppPathResolver $appPathResolver,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('how_many_best', InputArgument::OPTIONAL, 'Iterations value', 10)
        ;
    }

    /**
     * @throws BadDataException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $howManyBest = $input->getArgument('how_many_best');

        $finder = (new Finder())
            ->files()
            ->in($this->appPathResolver->getAppPath('optimal'))
            ->depth('== 0')
            ->name('*_optimal_parameters_results.json')
        ;

        $topResults = $this->getTopResults($finder, $howManyBest);

        dump(new TestParametersVO(
            alpha: round(Average::mean($topResults->column('alpha'))),
            beta: round(Average::mean($topResults->column('beta'))),
            distanceCoefficient: (int) round(Average::mean($topResults->column('distanceCoefficient'))),
            evaporation: Average::mean($topResults->column('evaporation')),
            antFactor: Average::mean($topResults->column('antFactor')),
            c: Average::mean($topResults->column('c')),
        ));

        $io->success('OK');

        return Command::SUCCESS;
    }

    /**
     * @return CollectionInterface<object>
     */
    private function getTopResults(Finder $finder, int $howManyBest): CollectionInterface
    {
        $topResults = [];

        foreach ($finder as $file) {
            /** @var array<object> $data */
            $data = json_decode(file_get_contents($file->getRealPath()));
            $topResults = array_merge($topResults, array_map(
                fn (object $result) => $result->parameters,
                array_slice($data, 0, $howManyBest)
            ));
        }

        return new Collection('object', $topResults);
    }
}
