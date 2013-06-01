<?php

namespace  Trismegiste\PastaDebug;

require_once __DIR__ . '/vendor/autoload.php';
require_once 'PHPUnit/Autoload.php';

$packageDir = __DIR__ . '/Tests/Fixtures/';

// filled with a traversing of package
$classToTrack = array(
    'Project\Service' => $packageDir . '/Service.php'
);

chdir($packageDir);
$cmd = new PhpUnit\Command($classToTrack);
$cmd->run(array('-c', $packageDir), false);

print_r(PhpUnit\Command::$callLink);

