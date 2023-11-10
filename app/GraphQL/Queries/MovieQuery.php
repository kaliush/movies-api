<?php

namespace App\GraphQL\Queries;

use App\Interfaces\ResolverInterface;
use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

final class MovieQuery implements ResolverInterface
{
    public function resolve($root, array $args): LengthAwarePaginator
    {
        return Movie::when(isset($args['search']), function ($query) use ($args) {
            $query->search($args['search']);
        })
            ->when(isset($args['type']), function ($query) use ($args) {
                $query->where('type', $args['type']);
            })
            ->when(isset($args['year']), function ($query) use ($args) {
                $query->where('year', $args['year']);
            })
            ->paginate($args['page'] ?? 10);
    }
}
