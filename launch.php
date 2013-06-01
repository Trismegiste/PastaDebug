<?php

namespace Trismegiste\Intricate;

require_once __DIR__ . '/vendor/autoload.php';
require_once 'PHPUnit/Autoload.php';

$phpUnitConfig = __DIR__ . '/Tests/Fixtures/';
$fileToTrack = array(__DIR__ . '/Tests/Fixtures/Service.php');


$cmd = new \PHPUnit_TextUI_Command();
$cmd->run(array('-c', $phpUnitConfig, '--bootstrap', __DIR__ . '/catcher.php'));
