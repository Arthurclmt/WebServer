<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appareil;
use App\Models\UserLog;


class AppareilController extends Controller{
    public function index(Request $request){
        $query = Appareil::query();

        // Recherche
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%'); 
        }

        $appareils = $query->get();

        return view('appareil.rechercheAppareil', compact('appareils'));
    }

    public function show($id)
{
    // On récupère l'appareil ou on renvoie une erreur 404 s'il n'existe pas
    $appareil = Appareil::findOrFail($id);

    $user = auth()->user();

    $oldLevel = $user->level; // On stocke l'ancien niveau

    if ($user) {
        $gain = 10; // On définit le montant de points

        // Optionnel : On vérifie si l'utilisateur a déjà consulté cet appareil aujourd'hui 
        // pour éviter qu'il gagne 1000 points en spammant F5
        $dejaConsulte = \App\Models\UserLog::where('user_id', $user->id)
            ->where('action', 'Consultation appareil ID: ' . $id)
            ->whereDate('created_at', now()->today())
            ->exists();

        if (!$dejaConsulte) {
            // Ajouter les points
            $user->points += $gain;

            // Calcul du grade (Level)
            if ($user->points >= 50) {
                $user->level = 'expert';
            } elseif ($user->points >= 20) {
                $user->level = 'avance';
            } elseif ($user->points >= 10) {
                $user->level = 'intermediaire';
            }

            $user->save();

            // Créer le log pour l'historique
            \App\Models\UserLog::create([
                'user_id' => $user->id,
                'action' => 'Consultation appareil ID: ' . $id,
                'points_earned' => $gain,
            ]);
        }

        if ($user->level !== $oldLevel) {
            $user->save();
            // On crée une session flash pour dire à la vue d'afficher la fenêtre
            session()->flash('level_up', $user->level);
        } else {
            $user->save();
        }
    }

    return view('appareil.show', compact('appareil'));
}
}
