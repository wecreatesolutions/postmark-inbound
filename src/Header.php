<?php

namespace WeCreateSolutions\Postmark;

class Header
{
    public function __construct(public readonly string $name, public readonly string $value)
    {
    }
}
