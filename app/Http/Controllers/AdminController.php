<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AllowedMember;
use Illuminate\Http\Request;

class AdminController extends Controller
{
        public function index()
    {
        $users = User::orderBy('role', 'desc')->orderBy('pseudo')->get();
        
        // On ne récupère que ceux qui ne sont pas encore inscrits
        $allowedEmails = AllowedMember::where('is_registered', false)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        return view('admin.index', compact('users', 'allowedEmails'));
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

            public function storeAllowedEmail(Request $request)
    {
        $request->validate([
            // unique:table,colonne
            'email' => 'required|email|unique:allowed_members,email|unique:users,email',
        ], [
            'email.unique' => 'Cet utilisateur est déjà dans la liste ou déjà inscrit !'
        ]);

        AllowedMember::create([
            'email' => $request->email,
            'added_by' => auth()->id(),
        ]);

        return back()->with('success', "Email ajouté.");
    }

    public function destroyAllowedEmail(AllowedMember $allowedMember)
    {
        $allowedMember->delete();
        return back()->with('success', "L'email a été retiré de la liste.");
    }
}
