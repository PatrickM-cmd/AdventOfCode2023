<?php

namespace PatrMehr\AdventOfCode2023\Day05;

use PatrMehr\AdventOfCode2023\AbstractSolver;
use PatrMehr\AdventOfCode2023\Result\SolverResult;
use Symfony\Component\Console\Helper\ProgressBar;

class Solver extends AbstractSolver
{
    public static function solve(string $input): SolverResult
    {
        [$seeds, $rules] = self::parseInput(trim($input));

        // Part 1
        $seedParameter = self::applyRules($seeds, $rules);
        $locations = array_map(function ($seedParams) {
            return array_pop($seedParams);
        }, $seedParameter);

        //Part 2
        $seedChunks = array_chunk($seeds, 2);

        foreach ($seedChunks as $chunk) {
            $range = ['start' => $chunk[0], 'end' => $chunk[0] + $chunk[1] - 1];
            $minLocations[] = min(array_column(self::applyRulesToRange($range, $rules), 'start'));
        }

        self::$ans1 = min($locations);
        self::$ans2 = min($minLocations);

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

    protected static function applyRulesToRange($startRange, $rules)
    {
        $ranges[] = $startRange;

        foreach ($rules as $rule) {
            $newRanges = [];
            foreach ($rule as $step) {
                $ruleRange = ['start' => $step['source'], 'end' => $step['source'] + $step['length'] - 1];

                for ($count = 0; $count < count($ranges); $count++) {
                    $range = $ranges[$count];

                    $start = max($range['start'], $ruleRange['start']);
                    $end = min($range['end'], $ruleRange['end']);

                    if ($start <= $end) {
                        array_splice($ranges, $count--, 1);
                        $intersect = ['start' => $start, 'end' => $end];
                        $newRanges[] = ['start' => $intersect['start'] + $step['offset'], 'end' => $intersect['end'] + $step['offset']];

                        $diffs = self::compareRanges($range, $intersect);

                        if ($diffs) {
                            $ranges = array_merge($ranges, $diffs);
                        }
                    }
                }
            }
            $ranges = array_merge($ranges, $newRanges);
        }

        return $ranges;
    }

    protected static function compareRanges($range_one, $range_two)
    {
        if ($range_one['start'] > $range_two['start']) {
            return [$range_one];
        }

        if ($range_one['end'] < $range_two['end']) {
           return [$range_one];
        }

        $diffStart = max($range_one['start'], $range_two['start']);
        $diffEnd = min($range_one['end'], $range_two['end']);

        if ($range_one['start'] < $diffStart) {
            $ranges[] = ['start' => $range_one['start'], 'end' => $diffStart - 1];
        }

        if ($range_one['end'] > $diffEnd) {
            $ranges[] = ['start' => $diffEnd + 1, 'end' => $range_one['end']];
        }

        return $ranges ?? null;
    }

    protected static function applyRules($seeds, $rules)
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