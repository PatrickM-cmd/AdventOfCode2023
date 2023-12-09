<?php

namespace PatrMehr\AdventOfCode2023\Day04;

use PatrMehr\AdventOfCode2023\AbstractSolver;
use PatrMehr\AdventOfCode2023\Result\SolverResult;

class Solver extends AbstractSolver
{
    public static function solve(string $input): SolverResult
    {
        $parsedInput = self::parseInputToLinesArray($input);

        $gamePoints = [];
        $gameWins = [];
        foreach ($parsedInput as $line) {
            preg_match('/[^:]*/', $line, $matches);
            $gameId = trim(substr($matches[0], 5));

            preg_match('/:(.*)/', $line, $matches);
            $game = explode('|', trim($matches[1]));

            $gamePoints[$gameId] = 0;
            $gameWins[$gameId] = 0;
            foreach (explode(' ', trim($game[0])) as $winningNumber) {
                foreach (explode(' ', trim($game[1])) as $selectedNumber) {
                    if ($winningNumber == $selectedNumber && is_numeric($selectedNumber)) {
                        if ($gamePoints[$gameId] == 0) {
                            $gamePoints[$gameId] = 1;
                        } else {
                            $gamePoints[$gameId] *= 2;
                        }

                        $gameWins[$gameId] += 1;
                    }
                }
            }
        }

        $gameCards = array_fill(1, count($gameWins), 1);
        foreach ($gameWins as $gameId => $win) {
            for ($loop = 0; $loop < $gameCards[$gameId]; $loop++) {
                for ($count = 1; $count <= $win; $count++) {
                    $gameCards[$gameId + $count] += 1;
                }
            }
        }

        self::$ans1 = array_sum($gamePoints);
        self::$ans2 = array_sum($gameCards);

        return parent::solve($input);
    }
}