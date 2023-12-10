<?php

namespace PatrMehr\AdventOfCode2023\Day06;

use PatrMehr\AdventOfCode2023\AbstractSolver;
use PatrMehr\AdventOfCode2023\Result\SolverResult;

class Solver extends AbstractSolver
{
    public static function solve(string $input): SolverResult
    {
        [$times, $distances] = self::parseInputToLinesArray($input);

        $times_1 = self::getArrayFromString($times);
        $distances_1 = self::getArrayFromString($distances);
        $variants_1 = self::beatRace(array_combine($times_1, $distances_1));

        $times_2[] = implode('', self::getArrayFromString($times));
        $distances_2[] = implode('', self::getArrayFromString($distances));
        $variants_2 = self::beatRace(array_combine($times_2, $distances_2));

        $product_1 = array_reduce($variants_1, function ($carry, $item) {
            return $carry * $item;
        }, 1);

        $product_2 = array_reduce($variants_2, function ($carry, $item) {
            return $carry * $item;
        }, 1);

        self::$ans1 = $product_1;
        self::$ans2 = $product_2;

        return parent::solve($input);
    }

    protected static function beatRace($race)
    {
        $speed = 1;
        $variants = [];
        foreach ($race as $time => $distance) {
            $max = false;
            $min = false;
            for ($pressed = 1; $pressed <= $time; $pressed++) {
                if ((($time - $pressed) * ($pressed * $speed)) > $distance) {
                    $min = $pressed;
                    break;
                }
            }

            for ($pressed = $time; $pressed >= 1; $pressed--) {
                if ((($time - $pressed) * ($pressed * $speed)) > $distance) {
                    $max = $pressed;
                    break;
                }
            }
            $variants[] = $max - $min + 1;
        }

        return $variants;
    }

    protected static function getArrayFromString($string) : array
    {
        preg_match('/:(.*)/', $string, $matches);
        return array_filter(explode(' ', trim($matches[1])), 'strlen');
    }
}