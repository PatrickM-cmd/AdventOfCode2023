<?php

namespace PatrMehr\AdventOfCode2023\Day07;

use PatrMehr\AdventOfCode2023\AbstractSolver;
use PatrMehr\AdventOfCode2023\Result\SolverResult;

class Solver extends AbstractSolver
{
    public static function solve(string $input): SolverResult
    {
        $parsedInput = self::parseInputToLinesArray($input);

        self::$ans1 = array_sum(self::sortInput($parsedInput));
        self::$ans2 = array_sum(self::sortInput($parsedInput, true));

        return parent::solve($input);
    }

    protected static function sortInput($parsedInput, $use_jokers = false)
    {
        uksort($parsedInput, function ($a, $b) use ($use_jokers) {
            $a = self::convertHandToScore($a, $use_jokers);
            $b = self::convertHandToScore($b, $use_jokers);

            return ($a < $b) ? -1 : 1;
        });

        array_walk($parsedInput, function (&$bid, $hand) use ($parsedInput) {
            $keys = array_keys($parsedInput);
            $bid *= array_search($hand, $keys) + 1;
        });

        return $parsedInput;
    }

    protected static function parseInputToLinesArray($input): array
    {
        $input = parent::parseInputToLinesArray($input);

        $parsedInput = [];
        foreach ($input as $line) {
            [$hand, $bid] = explode(' ', $line);
            $parsedInput[$hand] = $bid;
        }

        return $parsedInput;
    }

    protected static function convertHandToScore($hand, $use_jokers = false) : int
    {
        $scores = array_flip(['11111', '2111', '221', '311', '32', '41', '5']);
        $cardValues = array_flip([2, 3, 4, 5, 6, 7, 8, 9, 'T', 'J', 'Q', 'K', 'A']);
        $jokerCardValues = array_flip(['J', 2, 3, 4, 5, 6, 7, 8, 9, 'T', 'Q', 'K', 'A']);

        $countedChars = [];
        foreach (count_chars($hand, 1) as $charKey => $charCount) {
            $countedChars[chr($charKey)] = $charCount;
        }

        arsort($countedChars);

        if ($use_jokers && isset($countedChars['J']) && $countedChars['J'] < 5) {
            $joker = $countedChars['J'];
            unset($countedChars['J']);
            $countedChars[array_key_first($countedChars)] += $joker;
        }

        $score = $scores[implode('', $countedChars)];

        $score = pow($score + 1, 10);
        foreach (str_split($hand) as $value) {
            $score *= 100;
            $score += $use_jokers ? $jokerCardValues[$value] : $cardValues[$value];
        }
        return (int)$score;
    }
}