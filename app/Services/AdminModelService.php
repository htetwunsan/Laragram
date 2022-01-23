<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\View;

class AdminModelService
{
    public function shareAdminModel($class, array $foreignKeys = [])
    {
        $modelName = class_basename($class);;
        $adminModels = View::shared('adminModels', []);
        if (array_key_exists($modelName, $adminModels)) {
            $adminModel = $adminModels[$modelName];
            $adminModel['primaryKey'] = 'id';
        } else {
            throw new Exception('Requested ModelController not found in Admin/Resources folder.');
        }

        $adminModel['foreignKeys'] = $foreignKeys;

        View::share('adminModel', $adminModel);
    }
}
