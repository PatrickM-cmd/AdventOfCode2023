<?php

namespace PatrMehr\AdventOfCode2023\Commands;

class InputFetcher
{
    public function getLiveInput($day)
    {
        $ch = curl_init();
        $url = 'https://adventofcode.com/2023/day/'. $day .'/input';
        $sessionCookie = $_ENV['SESSION_ID'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Cookie: session='. $sessionCookie
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            die('Error occurred: ' . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }

    public function getTestInput($day)
    {
        $fileName = __DIR__ . '\..\Day' . str_pad('0', 2, $day) . '\input-test';

        if (file_exists($fileName)) {
            return file_get_contents($fileName);
        }

        return false;
    }
}