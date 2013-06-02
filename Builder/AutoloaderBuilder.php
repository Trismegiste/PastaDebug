<?php

/*
 * Mondrian
 */

namespace Trismegiste\PastaDebug\Builder;

use Trismegiste\Mondrian\Builder\Compiler\AbstractTraverser;
use Trismegiste\PastaDebug\Visitor\ClassMapperCollector;

/**
 * AutoloaderBuilder builds the compiler of class map autoloader
 */
class AutoloaderBuilder extends AbstractTraverser
{

    protected $mapping;

    public function __construct(\ArrayObject $map)
    {
        $this->mapping = $map;
    }

    public function buildCollectors()
    {
        return array(
            new ClassMapperCollector($this->mapping)
        );
    }

    public function buildContext()
    {
        
    }

}