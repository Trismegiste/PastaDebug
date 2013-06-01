<?php

namespace Project;

class Provider implements \IteratorAggregate
{

    public function getIterator()
    {
        return new \ArrayIterator(array(21, 3, 7, 11));
    }

}