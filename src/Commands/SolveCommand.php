<?php

namespace PatrMehr\AdventOfCode2023\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SolveCommand extends Command
{
    protected function configure()
    {
        $this->setName('aoc2023:solve')
            ->setDescription('Solve Advent Of Code 2023 Days')
            ->addArgument('day', InputArgument::REQUIRED, 'Day')
            ->addArgument('fetchInput', InputArgument::OPTIONAL, 'Fetches AoC Input Data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $day = (int)$input->getArgument('day');
        $fetchInput = (bool)$input->getArgument('fetchInput');
        $fetcher = new InputFetcher();

        if ($fetchInput) {
            $input = $fetcher->getLiveInput($day);
        } else {
            $input = $fetcher->getTestInput($day);
        }

        if (!$input){
            $output->writeln('Input is empty!');
            return Command::FAILURE;
        }

        $class = 'PatrMehr\AdventOfCode2023\Day' . str_pad('0', 2, $day) . '\Solver';
        if (class_exists($class)) {
            $result = call_user_func([$class, 'solve'], $input);
        } else {
            $output->writeln('Solver not available');
            return Command::FAILURE;
        }

        $output->writeln('Result 1: ' . $result->ans1);
        $output->writeln('Result 2: ' . $result->ans2);
        return Command::SUCCESS;
    }
}