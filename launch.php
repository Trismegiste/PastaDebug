<?php

namespace Trismegiste\Intricate;

require_once __DIR__ . '/vendor/autoload.php';
require_once 'PHPUnit/Autoload.php';

$packageDir = __DIR__ . '/Tests/Fixtures/';

$classToTrack = array(
    'Project\Service' => $packageDir . '/Service.php'
);

chdir($packageDir);
$cmd = new PhpUnit\Command($classToTrack);
$cmd->run(array('-c', $packageDir));

file_put_contents('resport.txt', PhpUnit\Command::$callLink);

