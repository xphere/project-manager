<?php

require_once __DIR__ . '/../vendor/autoload.php';

$application = new \Berny\ProjectManager\Application(dirname(__DIR__));
$application->run();
