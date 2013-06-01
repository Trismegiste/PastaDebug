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

    protected $currentClass;
    protected $currentMethod;

    public function enterNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {

            case 'Stmt_Class':
                $this->currentClass = $node->name;
                break;

            case 'Stmt_ClassMethod':
                $this->currentMethod = $node->name;
                break;
        }
    }

    public function leaveNode(\PHPParser_Node $node)
    {
        switch ($node->getType()) {

            case 'Expr_MethodCall':
                if (!($node->var->getType() == 'Expr_Variable' &&
                        $node->var->name == 'this')) {
                    $newArg = array(
                        new \PHPParser_Node_Scalar_String($this->currentClass),
                        new \PHPParser_Node_Scalar_String($this->currentMethod),
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