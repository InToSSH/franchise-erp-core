<?php


namespace App\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait DatatableTrait
{
    /**
     * Takes base Eloquent query for CRUD tables and applies sorting and pagination coming from request from
     * DataTable Vue component.
     *
     * @param $query
     * @param string $default_sort_field Default order field with syntax: "db_field_name[:ASC|DESC]"
     * @param ?array $searchable_fields
     * @param array $sortable_fields
     * @param int $default_per_page
     * @return mixed
     */
    public function getDatatableResults(
        Builder $query,
        string $default_sort_field,
        ?array $searchable_fields = null,
        array $sortable_fields = [],
        int $default_per_page = 20,
    ) {
        $request = request();
        if ($searchable_fields && $request->has('searchValue') && !empty($request->input('searchValue'))) {
            if ($request->has('searchField')) {
                $searchFields = explode(';', $request->input('searchField'));
                foreach ($searchFields as $k => $field) {
                    $searchFields[$k] = trim($field);
                }
                $searchable_fields = array_intersect($searchable_fields, $searchFields);
            }
            $query->whereLike($searchable_fields, $request->input('searchValue'));
        }

        $sortFieldParts = explode(':', $default_sort_field);
        $sortField = $sortFieldParts[0];
        $sortOrder = isset($sortFieldParts[1]) ? ($sortFieldParts[1] === 'DESC' ? 'DESC' : 'ASC') : 'ASC';

        if ($request->has('sortField') && in_array($request->input('sortField'), $sortable_fields)) {
            $sortField = $request->input('sortField');
            $sortOrder = 'ASC';
        }

        if ($request->has('sortOrder')) {
            $sortOrder = $request->input('sortOrder') === '-1' ? 'DESC' : 'ASC';
        }

        if (strpos($sortField, '.') === false) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->orderByRelation($sortField, $sortOrder);
        }

        if ($request->has('perPage') && $request->input('perPage') === 'all') {
            return $query->get();
        }

        return $query->paginate(
            ($request->has('perPage') && is_numeric($request->input('perPage')))
                ? $request->input('perPage')
                : $default_per_page
        );
    }

    /**
     * Takes a Collection for CRUD tables and applies sorting and pagination coming from request from
     * DataTable Vue component.
     * WARNING: Not to be used with large collections because it loads the whole collection first
     *
     * @param Collection $collection
     * @param Request $request
     * @param string $default_sort_field
     * @param array|null $searchable_fields
     * @param array $sortable_fields
     * @param int $default_per_page
     * @return LengthAwarePaginator
     */
    public function getCollectionDatatableResults(
        Collection $collection,
        Request $request,
        string $default_sort_field,
        ?array $searchable_fields = null,
        array $sortable_fields = [],
        int $default_per_page = 20,
    ): LengthAwarePaginator
    {
        if ($searchable_fields && $request->has('search') && !empty($request->input('search'))) {
            // Collection uses custom macro 'whereAnyKeyLike' specified in App\Providers\AppServiceProvider
            $collection = $collection->whereAnyKeyLike($searchable_fields, $request->input('search'));
        }

        if ($request->has('sortField') && in_array($request->input('sortField'), $sortable_fields)) {
            $sortOrder = $request->has('sortOrder') ? $request->input('sortOrder') : '1';
            if ($sortOrder == '-1') {
                $collection = $collection->sortByDesc($request->input('sortField'));
            } else {
                $collection = $collection->sortBy($request->input('sortField'));
            }
        } else {
            $sort_field = explode(":", $default_sort_field);
            $collection = isset($sort_field[1]) && $sort_field[1] == 'DESC' ? $collection->sortByDesc($sort_field[0]) : $collection->sortBy($sort_field[0]);
        }

        return $collection->paginate(
            ($request->has('rows') && is_numeric($request->input('rows')))
                ? $request->input('rows')
                : $default_per_page
        );

    }
}
