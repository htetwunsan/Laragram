<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $q = '%' . $request->q . '%';

        $notIn = collect(Auth::user()->blockingsUserIds())->merge(Auth::user()->blockersUserIds());

        $users = User::whereNotIn('id', $notIn)
            ->where(function ($query) use ($q) {
                $query->where('username', 'LIKE', $q)
                    ->orWhere('name', 'LIKE', $q)
                    ->orWhere('email', 'LIKE', $q);
            })->take(10)->get();

        return $users;
    }
}
