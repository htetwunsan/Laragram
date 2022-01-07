<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureUserIsNotAuthUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockingController extends Controller
{
    public function block(User $user, Request $request)
    {
        // try {
        //     Auth::user()->block($user);
        // } catch (Exception $e) {
        //     if ($request->expectsJson()) {
        //         return response()->json(['error' => $e->getMessage()], 400);
        //     }
        //     return back()->with('error', $e->getMessage());
        // }

        Auth::user()->block($user);

        if ($request->expectsJson()) {
            return response()->json(['success' => 'You have blocked ' . $user->username . '.']);
        }

        return back()->with('success', 'Blocked');
    }

    public function unblock(User $user, Request $request)
    {
        // try {
        //     Auth::user()->unblock($user);
        // } catch (Exception $e) {
        //     if ($request->expectsJson()) {
        //         return response()->json(['error' => $e->getMessage()], 400);
        //     }
        //     return back()->with('error', $e->getMessage());
        // }

        Auth::user()->unblock($user);

        if ($request->expectsJson()) {
            return response()->json(['success' => 'You have unblocked ' . $user->username . '.']);
        }

        return back()->with('success', 'Unblocked');
    }

    public function isBlocking(User $user)
    {
        return response()->json(['result' => Auth::user()->isBlocking($user)]);
    }
}
