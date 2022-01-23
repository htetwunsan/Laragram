<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class IndexService
{
    public function processRequest($request, $class, $filters)
    {
        $modelName = class_basename($class);
        $modelNamePluralLowercase = Str::lower(Str::plural($modelName));
        $types = [];
        foreach (Schema::getColumnListing($modelNamePluralLowercase) as $column) {
            $types[$column] = Schema::getColumnType($modelNamePluralLowercase, $column);
        }
        DB::table('users')->get();
        $query = $class::query();

        if ($request->filters) {
            $query->where(function ($query) use ($request, $types) {
                foreach ($request->filters as $column => $value) {
                    if (Arr::exists($types, $column)) {
                        switch ($types[$column]) {
                            case 'boolean':
                                $query->where($column, $value);
                                break;
                            case 'datetime':
                                switch ($value) {
                                    case 'today':
                                        $query->whereDate($column, Carbon::today());
                                        break;
                                    case 'past 7 days':
                                        $query->whereDate($column, '>=', Carbon::today()->subDays(7));
                                        break;
                                    case 'this month':
                                        $query->whereDate($column, '>=', Carbon::today()->subMonth());
                                        break;
                                    case 'this year':
                                        $query->whereDate($column, '>=', Carbon::today()->subYear());
                                        break;
                                    default:
                                        break;
                                }
                                break;
                            default:
                                break;
                        }
                    }
                }
            });
        }

        if ($request->search) {
            $query->where(function ($query) use ($request, $types) {
                foreach ($types as $column => $definition) {
                    $query->orWhere($column, 'LIKE', '%' . $request->search . '%');
                }
            });
        }

        if ($request->orders) {
            foreach ($request->orders as $column => $direction) {
                if (Arr::exists($types, $column)) {
                    $query->orderBy($column, $direction);
                }
            }
        }

        $models = $query->paginate(20)->withQueryString();

        View::share('models', $models);
        View::share('types', $types);
        View::share('filters', $filters);
    }
}
