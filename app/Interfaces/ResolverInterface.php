<?php

namespace App\Interfaces;

/**
 * Interface for GraphQL query and mutation resolvers.
 *
 * This interface defines a standard structure for resolving GraphQL queries
 * and mutations. Implementations should define how to handle specific GraphQL
 * operations, such as fetching data or performing actions based on provided arguments.
 */
interface ResolverInterface
{
    /**
     * Resolve a GraphQL query or mutation.
     *
     * @param mixed $root The initial value passed to the resolver, often null for top-level queries.
     * @param array $args The arguments provided in the GraphQL query or mutation.
     *
     * @return iterable The result of the resolver, often a model or a collection of models.
     */
    public function resolve($root, array $args): iterable;
}
