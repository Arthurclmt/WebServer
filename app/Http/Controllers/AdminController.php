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
  
    public function users()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $users = User::all();
        return view('admin.users', ['users' => $users]);
    }

    public function updateEmail(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update(['email' => $request->email]);

        return back()->with('success', "Email de {$user->pseudo} mis à jour.");
    }

    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return back()->with('success', "Le compte de {$user->pseudo} a été supprimé.");
    }
}
