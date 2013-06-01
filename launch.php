<?php

namespace Trismegiste\Intricate;

require_once __DIR__ . '/vendor/autoload.php';
require_once 'PHPUnit/Autoload.php';

$phpUnitConfig = __DIR__ . '/Tests/Fixtures/';

$classToTrack = array(
    'Project\Service' => __DIR__ . '/Tests/Fixtures/Service.php'
);

$cmd = new PhpUnit\Command($classToTrack);
$cmd->run(array('-c', $phpUnitConfig));
