<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Prettus\Repository\Eloquent\BaseRepository;

abstract class BaseRepositoryEloquent extends BaseRepository
{
    /**
     * Generate a LengthAwarePaginator class based from the count of Builder class
     *
     * @param Builder $queryBuilder
     * @return LengthAwarePaginator
     */
    protected function createPaginationFromBuilder(Builder $queryBuilder): LengthAwarePaginator
    {
        $itemsPerPage = Request::get('itemsPerPage', config('repository.pagination.limit', 50));
        $page = Request::get('page', 1);

        $count = $queryBuilder->getCountForPagination();

        $data = $queryBuilder->forPage($page, $itemsPerPage)->get();

        return new LengthAwarePaginator($data, $count, $itemsPerPage, $page);
    }

    /**
     * Hydrate items in the paginator collection to the specified class
     *
     * @param LengthAwarePaginator $paginator
     * @param Model $class
     * @return LengthAwarePaginator
     */
    protected function hydratePaginationItems(LengthAwarePaginator $paginator, Model $class): LengthAwarePaginator
    {
        $items = [];

        foreach ($paginator->items() as $key => $item) {
            $items[] = (get_class($item) === 'stdClass')
                ? (array)$item
                : $item->toArray();
        }

        return $paginator->setCollection($class::hydrate($items));
    }

    /**
     * Hydrate collection to the specified class
     *
     * @param Collection $collection
     * @param Model $class
     * @return Collection
     */
    protected function hydrateCollection(Collection $collection, Model $class): Collection
    {
        $items = [];

        foreach ($collection as $key => $item) {
            $items[] = (get_class($item) === 'stdClass')
                ? (array)$item
                : $item->toArray();
        }

        return $class::hydrate($items);
    }
}
