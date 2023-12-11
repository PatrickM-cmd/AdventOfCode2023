<?php

namespace PatrMehr\AdventOfCode2023\Day08;

use PatrMehr\AdventOfCode2023\AbstractSolver;
use PatrMehr\AdventOfCode2023\Result\SolverResult;

class Solver extends AbstractSolver
{
    public static function solve(string $input): SolverResult
    {
        $parsedInput = self::parseInputToLinesArray($input);

        [$elements, $instructions, $startElements] = self::parseElements($parsedInput);

        self::$ans1 = self::applyInstructions($elements, $instructions, 'AAA', 'ZZZ');
        self::$ans2 = self::getLcdOfAppliedInstructions($elements, $instructions, $startElements, 'Z');

        return parent::solve($input);
    }
    protected static function applyInstructions($elements, $instructions, $startElement, $ending)
    {
        $finalSteps = 0;
        $finished = false;
        while (!$finished) {
            foreach ($instructions as $instruction) {
                $startElement = $elements[$startElement][$instruction == 'L' ? 0 : 1];
                $finalSteps++;

                if (str_ends_with($startElement, $ending)) {
                    $finished = true;
                }
            }
        }

        return $finalSteps;
    }

    protected static function getLcdOfAppliedInstructions($elements, $instructions, $startElements, $ending)
    {
        $finalSteps = [];

        foreach ($startElements as $startElement) {
            $finalSteps[] = self::applyInstructions($elements, $instructions, $startElement, $ending);
        }

        $lcm = array_pop($finalSteps);
        foreach ($finalSteps as $steps) {
            $lcm = $lcm * $steps / gmp_gcd($lcm, $steps);
        }

        return $lcm;
    }

    protected static function parseElements($input)
    {
        $instructions = str_split(array_shift($input));
        unset($input[0]);

        $elements = [];
        $startElements = [];
        foreach ($input as $element) {
            $element = explode(' = ', $element);
            $elements[$element[0]] = explode(', ', str_replace(['(', ')'], '', $element[1]));

            if (str_ends_with($element[0], 'A')) {
                $startElements[] = $element[0];
            }
        }

        return [$elements, $instructions, $startElements];
    }
}