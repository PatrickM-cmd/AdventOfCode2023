<?php

namespace PatrMehr\AdventOfCode2023\Day02;

use PatrMehr\AdventOfCode2023\Result\SolverResult;

class Solver
{
    public static function solve(string $input) : SolverResult
    {
        $loadedCubes = [
            'red' => 12,
            'green' => 13,
            'blue' => 14,
        ];

        $ans1 = 0;
        $ans2 = 0;
        foreach (preg_split('/\r\n|\r|\n/', $input) as $item) {

            if (!$item) {
                continue;
            }

            preg_match('/[^:]*/', $item, $matches);
            $gameId = substr($matches[0], 5);

            preg_match('/:(.*)/', $item, $matches);
            $gameSets = explode(';', trim($matches[1]));

            $gameSet = [
                'red' => 0,
                'green' => 0,
                'blue' => 0,
            ];

            foreach ($gameSets as $set) {
                $cubes = explode(',', $set);

                foreach ($cubes as $cube) {
                    $parts = preg_split('/\s+/', trim($cube));
                    if ($gameSet[$parts[1]] < (int)$parts[0]) {
                        $gameSet[$parts[1]] = (int)$parts[0];
                    }
                }
            }

            $possibilities = array_map(function ($loaded, $game) {
                return $loaded >= $game;
            }, $loadedCubes, $gameSet);

            $isPossible = array_reduce($possibilities, function ($carry, $item) {
                return $carry && $item;
            }, true);

            if ($isPossible) {
                $ans1 += (int)$gameId;
            }

            $ans2 += $gameSet['red'] * $gameSet['green'] * $gameSet['blue'];
        }

        return new SolverResult($ans1, $ans2);
    }
}