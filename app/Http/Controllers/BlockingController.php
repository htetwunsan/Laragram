<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureUserIsNotAuthUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class BlockingController extends Controller
{
    public function block(User $user, Request $request)
    {
        try {
            auth()->user()->block($user);
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
            return back()->with('error', $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => 'You have blocked ' . $user->username . '.']);
        }

        return back()->with('success', 'Blocked');
    }

    public function unblock(User $user, Request $request)
    {
        try {
            auth()->user()->unblock($user);
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
            return back()->with('error', $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => 'You have unblocked ' . $user->username . '.']);
        }

        return back()->with('success', 'Unblocked');
    }
}
