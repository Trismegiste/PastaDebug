<?php

/*
 * Intricate
 */

namespace Trismegiste\Intricate\Visitor;

/**
 * TraceOn is ...
 *
 * @author flo
 */
class TraceOn extends \PHPParser_NodeVisitorAbstract
{

    public function leaveNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {
            case 'Expr_MethodCall':
                $args = $node->args;
                array_unshift($args, new \PHPParser_Node_Scalar_String($node->name));
                array_unshift($args, $node->var);
                return new \PHPParser_Node_Expr_FuncCall(new \PHPParser_Node_Name('method_call_catcher'), $args);
                return array(
                    new \PHPParser_Node_Stmt_Echo(array(new \PHPParser_Node_Scalar_String('toto')))
                    ,
                    $node
                );
                break;
        }
    }

}