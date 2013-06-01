<?php

use Trismegiste\Intricate\Visitor\TraceOn;

spl_autoload_register(function($class) {
            if ($class == 'Project\Service') {
                transform_and_eval($class, __DIR__ . '/Tests/Fixtures/Service.php');
            }
        }, true);

require_once __DIR__ . '/Tests/Fixtures/Tests/bootstrap.php';

function transform_and_eval($class, $filename)
{
    $parser = new \PHPParser_Parser(new \PHPParser_Lexer());
    $stmt = $parser->parse(file_get_contents($filename));
    $traver = new \PHPParser_NodeTraverser();
    $traver->addVisitor(new TraceOn());
    $changed = $traver->traverse($stmt);
    $pp = new \PHPParser_PrettyPrinter_Default();
    $newContent = $pp->prettyPrint($changed);
    eval($newContent);
}

function method_call_catcher($obj, $method, $arg = array())
{
    echo get_class($obj), '::', $method, PHP_EOL;
    return call_user_func_array(array($obj, $method), $arg);
}