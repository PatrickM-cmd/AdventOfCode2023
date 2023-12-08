#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use PatrMehr\AdventOfCode2023\Commands\SolveCommand;
use Symfony\Component\Console\Application;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

$application = new Application();

$application->add(new SolveCommand());

$application->run();