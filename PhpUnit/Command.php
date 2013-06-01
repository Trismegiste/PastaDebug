<?php

/*
 * Intricate
 */

namespace Trismegiste\Intricate\PhpUnit;

use Trismegiste\Intricate\Visitor\TraceOn;

/**
 * Command is a wrapper for lauching phpunit
 */
class Command extends \PHPUnit_TextUI_Command
{

    public static function transformAndEval($class, $filename)
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

    public static function methodCallCatcher($obj, $method, $arg = array())
    {
        echo get_class($obj), '::', $method, PHP_EOL;
        return call_user_func_array(array($obj, $method), $arg);
    }

    protected function handleBootstrap($filename)
    {
        spl_autoload_register(function($class) {
                    if ($class == 'Project\Service') {
                        \Trismegiste\Intricate\PhpUnit\Command::transformAndEval($class, __DIR__ . '/../Tests/Fixtures/Service.php');
                    }
                }, true);

        parent::handleBootstrap($filename);
    }

}