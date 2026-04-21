<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role', 'desc')->orderBy('pseudo')->get();
        return view('admin.index', compact('users'));
    }

    public function promote(User $user)
    {
        $user->update(['role' => 'admin']);
        return back()->with('success', "{$user->pseudo} est maintenant admin.");
    }

    public function demote(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous rétrograder vous-même.');
        }
        $user->update(['role' => 'simple']);
        return back()->with('success', "{$user->pseudo} n'est plus admin.");
    }

    public function ban(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous bannir vous-même.');
        }
        $user->update(['is_banned' => true]);
        return back()->with('success', "{$user->pseudo} a été banni.");
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);
        return back()->with('success', "{$user->pseudo} a été débanni.");
    }
}
