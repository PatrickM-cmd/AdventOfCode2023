<?php

namespace PatrMehr\AdventOfCode2023\Day09;

use PatrMehr\AdventOfCode2023\AbstractSolver;
use PatrMehr\AdventOfCode2023\Result\SolverResult;

class Solver extends AbstractSolver
{
    public static function solve(string $input): SolverResult
    {
        $parsedInput = self::parseInputToLinesArray($input);

        foreach ($parsedInput as $line) {
            $predictions[] = self::recursiveSequenceSolver(explode(' ', $line));
        }

        foreach ($parsedInput as $line) {
            $predictions_2[] = self::recursiveSequenceSolver(array_reverse(explode(' ', $line)));
        }

        self::$ans1 = array_sum($predictions ?? []);
        self::$ans2 = array_sum($predictions_2 ?? []);

        return parent::solve($input);
    }

    protected static function recursiveSequenceSolver($sequence)
    {
        $nonZeroElements = array_sum(array_map(function($value) {
            return $value != 0 ? 1 : 0;
        }, $sequence));

        if ($nonZeroElements == 0) {
            return 0;
        }

        $shiftedSequence = $sequence;
        array_shift($shiftedSequence);
        $shiftedSequence = array_pad($shiftedSequence, count($sequence), 0);

        $shiftedSequence = array_combine(range(1, count($sequence)), $shiftedSequence);

        $diff = array_map(function ($num_1, $num_2) {
            return $num_2 - $num_1;
        }, $sequence, $shiftedSequence);

        array_pop($diff);

        return $sequence[array_key_last($sequence)] + self::recursiveSequenceSolver($diff);
    }
}