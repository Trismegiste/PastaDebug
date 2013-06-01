<?php

/*
 * Intricate
 */

namespace Trismegiste\Intricate\Visitor;

/**
 * TraceOn is a visitor which catches all method call and redirects to
 * a global function to trace all calls with type-hinting.
 */
class TraceOn extends \PHPParser_NodeVisitorAbstract
{

    public function leaveNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {

            case 'Expr_MethodCall':
                if (!($node->var->getType() == 'Expr_Variable' &&
                        $node->var->name == 'this')) {
                    $newArg = array(
                        new \PHPParser_Node_Expr_ConstFetch(new \PHPParser_Node_Name('__METHOD__')),
                        $node->var,
                        new \PHPParser_Node_Scalar_String($node->name),
                        new \PHPParser_Node_Expr_Array($node->args)
                    );
                    return new \PHPParser_Node_Expr_StaticCall(
                            new \PHPParser_Node_Name_FullyQualified('Trismegiste\Intricate\PhpUnit\Command')
                            , 'methodCallCatcher'
                            , $newArg
                    );
                    return new \PHPParser_Node_Expr_FuncCall(
                            new \PHPParser_Node_Name('method_call_catcher'), $args);
                }
                break;
        }
    }

}