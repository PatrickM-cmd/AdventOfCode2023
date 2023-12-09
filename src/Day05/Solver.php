<?php

namespace PatrMehr\AdventOfCode2023\Day05;

use PatrMehr\AdventOfCode2023\AbstractSolver;
use PatrMehr\AdventOfCode2023\Result\SolverResult;

class Solver extends AbstractSolver
{
    public static function solve(string $input): SolverResult
    {
        [$seeds, $rules] = self::parseInput(trim($input));

        // Part 1
        $seedParameter = self::invokeRules($seeds, $rules);
        $locations = array_map(function ($seedParams) {
            return array_pop($seedParams);
        }, $seedParameter);

        //Part 2
        //TODO: Fix out of memory error
        $seedParameter2 = [];
        $seedChunks = array_chunk($seeds, 2);
        foreach ($seedChunks as $chunk) {
            for ($seedCount = $chunk[0]; $seedCount < $chunk[0] + $chunk[1]; $seedCount++) {
                $seedParameter2[] = self::invokeRules($seeds, $rules);
            }
        }
        $locations2 = array_map(function ($seedParams) {
            return array_pop($seedParams);
        }, $seedParameter2);

        self::$ans1 = min($locations);
        self::$ans2 = min($locations2);

        return parent::solve($input);
    }

    protected static function parseInput($input) : array
    {
        $explodedRules = preg_split('/\r\n\r\n|\r\r|\n\n/', trim($input));

        $seeds = array_shift($explodedRules);
        preg_match('/:(.*)/', $seeds, $matches);
        $parsedSeeds = explode(' ', trim($matches[1]));

        $rules = [];
        foreach ($explodedRules as $rule) {
            preg_match('/[^:]*/', $rule, $matches);
            $ruleId = trim($matches[0]);

            preg_match('/:\s*(.+)/s', $rule, $matches);
            foreach (self::parseInputToLinesArray($matches[1]) as $line) {
                [$dest, $source, $length] = explode(' ', trim($line));
                $offset = $dest - $source;

                $rules[$ruleId][] = compact('dest', 'source', 'length', 'offset');
            }
        }

        return [$parsedSeeds, $rules];
    }

    protected static function invokeRules($seeds, $rules)
    {
        $seedParameter = [];
        foreach ($seeds as $seed) {
            $seedParameter[$seed] = [];
            $nextRule = $seed;
            foreach ($rules as $key => $ruleStep){
                $nextRule = self::doStep($nextRule, $ruleStep);
                $seedParameter[$seed][$key] = $nextRule;
            }
        }

        return $seedParameter;
    }

    protected static function doStep($nextRule, $ruleStep)
    {
        foreach ($ruleStep as $step) {
            if ($nextRule >= $step['source'] && $nextRule < $step['source'] + $step['length']) {
                return $nextRule + $step['dest'] - $step['source'];
            }
        }

        return $nextRule;
    }
}