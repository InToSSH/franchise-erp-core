<?php

namespace App\Providers;

use App\Utils\Translation\CheckFieldIsTranslatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.force_https')) {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }

        $this->bootDbMacros();
    }

    private function bootDbMacros(): void
    {
        Builder::macro('whereLike', function ($attributes, ?string $searchTerm, bool $isOr = false) {
            if ($searchTerm) {
                $this->{$isOr ? 'orWhere' : 'where'}(function (Builder $query) use ($attributes, $searchTerm) {
                    foreach (Arr::wrap($attributes) as $attribute) {
                        $query->when(
                            str_contains($attribute, '.'),
                            function (Builder $query) use ($attribute, $searchTerm) {
                                [$relationName, $relationAttribute] = explode('.', $attribute);

                                $query->orWhereHas(
                                    $relationName,
                                    function (Builder $query) use ($relationAttribute, $searchTerm) {
                                        $query->whereRaw('LOWER(' . $relationAttribute . ') LIKE ?', ['%' . strtolower($searchTerm) . '%']);
                                    }
                                );
                            },
                            function (Builder $query) use ($attribute, $searchTerm) {
                                $query->orWhereRaw('LOWER(' . $attribute . ') LIKE ?', ['%' . strtolower($searchTerm) . '%']);
                            }
                        );
                    }
                });
            }

            return $this;
        });

        Builder::macro('orderByRelation', function (string $searchColumn, string $dir) {
            list($relation, $column) = explode('.', $searchColumn);
            $relation_model = $this->getRelation($relation)->getModel();
            $relation_table = $relation_model->getTable();
            $relation_foreign_key = $this->getRelation($relation)->getForeignKeyName();
            $query_table = $this->getModel()->getTable();

            $this->select($query_table . '.*')
                ->leftJoin($relation_table, $query_table . '.' . $relation_foreign_key, '=', $relation_table . '.id')
                ->orderBy($relation_table . '.' . $column, $dir);

            $this->orderBy($relation_table . '.' . $column, $dir);

            return $this;
        });

        Collection::macro('paginate', function ($perPage, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(), // $items
                $this->count(),                  // $total
                $perPage,
                $page,
                [                                // $options
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        Collection::macro('whereAnyKeyLike', function (array $attributes, string $searchTerm) {
            return $this->filter(function ($value, $key) use ($attributes, $searchTerm) {
                return (bool) count(preg_grep('~' . $searchTerm . '~', array_intersect_key($value->toArray(), array_flip($attributes))));
            });
        });
    }
}
