<?php

namespace Project;

class Service
{

    protected $data;

    public function __construct(\IteratorAggregate $data)
    {
        $this->data = $data->getIterator();
        $cst = __DIR__;
    }

    public function compute()
    {
        $sum = 0;
        foreach ($this->data as $item) {
            $sum += $item;
        }

        return $sum;
    }

}