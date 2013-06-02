<?php

/*
 * Intricate
 */

namespace Trismegiste\PastaDebug\Visitor;

/**
 * TraceOn is a visitor which catches all method call and redirects to
 * a global function to trace all calls with type-hinting.
 */
class TraceOn extends \PHPParser_NodeVisitorAbstract
{

    protected $filename;

    public function __construct($fch)
    {
        $this->filename = $fch;
    }

    public function leaveNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {

            case 'Scalar_DirConst':
                return new \PHPParser_Node_Scalar_String(dirname($this->filename));
                break;

            case 'Expr_MethodCall':
                if (!($node->var->getType() == 'Expr_Variable' &&
                        $node->var->name == 'this')) {

                    return $this->enterMethodCall($node);
                }
                break;
        }
    }

    protected function enterMethodCall(\PHPParser_Node_Expr_MethodCall $node)
    {
        $newArg = array(
            new \PHPParser_Node_Scalar_MethodConst(),
            $node->var,
            new \PHPParser_Node_Scalar_String($node->name),
            new \PHPParser_Node_Expr_Array($node->args)
        );

        return new \PHPParser_Node_Expr_StaticCall(
                new \PHPParser_Node_Name_FullyQualified('Trismegiste\PastaDebug\PhpUnit\Command')
                , 'methodCallCatcher'
                , $newArg
        );
    }

}