<?php

namespace PatrMehr\AdventOfCode2023;

use PatrMehr\AdventOfCode2023\Result\SolverResult;

class AbstractSolver
{
    protected static $ans1 = 0;
    protected static $ans2 = 0;

    protected static function parseInputToLinesArray($input) : array
    {
        return preg_split('/\r\n|\r|\n/', trim($input));
    }

    public static function solve(string $input) : SolverResult
    {
        return new SolverResult(self::$ans1, self::$ans2);
    }
}