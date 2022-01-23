<?php

namespace App\Services;

use Illuminate\Support\Str;

class AdminService
{

    public function getAdminModelsWithListCreateActions(): array
    {
        $adminModels = $this->getAdminModels();
        $adminModels = array_map(
            fn ($model) =>
            [
                $model => [
                    'name' => $model,
                    'index_url' => $this->getActionUrlOfModel($model, 'index'),
                    'create_url' => $this->getActionUrlOfModel($model, 'create'),
                    'store_url' => $this->getActionUrlOfModel($model, 'store')
                ]
            ],
            $adminModels
        );
        return array_merge([], ...$adminModels);
    }

    public function getAdminModels(): array
    {
        $resourcePath = app_path('Http/Controllers/Admin/Resources/*.php');

        return array_map(fn ($path) => basename($path, 'Controller.php'), glob($resourcePath));
    }

    public function getActionUrlOfModel($model, $action): string
    {
        return route('admin.' . Str::plural(Str::lower($model)) . '.' . $action);
    }
}
