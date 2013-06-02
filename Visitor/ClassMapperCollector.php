<?php

/*
 * Mondrian
 */

namespace Trismegiste\PastaDebug\Visitor;

use Trismegiste\Mondrian\Visitor\FqcnHelper;

/**
 * ClassMapperCollector is a visitor which collects hashmap class => file
 */
class ClassMapperCollector extends FqcnHelper
{

    protected $mapping;

    public function __construct(\ArrayObject $map)
    {
        $this->mapping = $map;
    }

    public function enterNode(\PHPParser_Node $node)
    {
        parent::enterNode($node);

        switch ($node->getType()) {

            case 'Stmt_Class':
                $this->mapping[$this->getNamespacedName($node)] = $this->currentPhpFile->getRealPath();
                break;
        }
    }

}