<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Edgaras\WhatToDo\Application;

$application = new Application();
$application->run();