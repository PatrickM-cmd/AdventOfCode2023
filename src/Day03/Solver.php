<?php

namespace PatrMehr\AdventOfCode2023\Day03;

use PatrMehr\AdventOfCode2023\AbstractSolver;
use PatrMehr\AdventOfCode2023\Result\SolverResult;

class Solver extends AbstractSolver
{
    public static function solve(string $input): SolverResult
    {
        $parsedInput = self::parseInputToLinesArray($input);

        $numberPosition = [];
        $symbols = [];
        $numbers = [];
        $id = 1;
        for ($y = 0; $y < count($parsedInput); $y++) {
            preg_match_all('!\d+!', $parsedInput[$y], $matches, PREG_OFFSET_CAPTURE);

            foreach ($matches[0] as $match) {
                for ($x = $match[1]; $x <= $match[1] + (strlen($match[0]) - 1); $x++) {
                    $numberPosition[$y][$x] = $id;
                    $numbers[$id] = $match[0];
                }
                $id++;
            }

            preg_match_all('/[^0-9.]+/', $parsedInput[$y], $matches, PREG_OFFSET_CAPTURE);

            foreach ($matches[0] as $match) {
                $symbols[] = [
                    'symbol' => $match[0],
                    'position_x' => $match[1],
                    'position_y' => $y
                ];
            }
        }

        $previous_id = -1;
        $symbol_id = 1;
        $gears = [];
        foreach ($symbols as $symbol) {
            for ($y = -1; $y <= 1; $y++) {
                for ($x = -1; $x <= 1; $x++) {
                    if ($numberPosition[$symbol['position_y'] + $y] ?? false) {
                        if ($numberPosition[$symbol['position_y'] + $y][$symbol['position_x'] + $x] ?? false) {
                            $numId = (int)$numberPosition[$symbol['position_y'] + $y][$symbol['position_x'] + $x];
                            if ($numId != $previous_id) {
                                self::$ans1 += $numbers[$numId];

                                if ($symbol['symbol'] == '*') {
                                    $gears[$symbol_id][] = $numbers[$numId];
                                }
                            }
                            $previous_id = $numId;
                        }
                    }
                }
            }
            $symbol_id++;
        }

        foreach ($gears as $gear) {
            if (count($gear) > 1) {
                self::$ans2 += $gear[0] * $gear[1];
            }
        }

        return parent::solve($input);
    }
}