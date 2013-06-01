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

    public static $classMap = array();
    public static $callLink = array();

    public function __construct($caughtClasses)
    {
        static::$classMap = $caughtClasses;
    }

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

    public static function methodCallCatcher($caller, $methodCaller, $obj, $method, array $arg = array())
    {
        static::$callLink[$caller][$methodCaller][get_class($obj)][$method] = true;
        echo $caller, '::', $methodCaller, ' -> ', get_class($obj), '::', $method, PHP_EOL;
        return call_user_func_array(array($obj, $method), $arg);
    }

    protected function handleBootstrap($filename)
    {
        spl_autoload_register(function($class) {
                    if (array_key_exists($class, Command::$classMap)) {
                        Command::transformAndEval($class, Command::$classMap[$class]);
                    }
                }, true);

        parent::handleBootstrap($filename);
    }

}