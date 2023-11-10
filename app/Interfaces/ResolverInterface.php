<?php

namespace App\Interfaces;

interface ResolverInterface
{
    public function resolve($root, array $args): iterable;
}
