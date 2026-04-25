<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
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
