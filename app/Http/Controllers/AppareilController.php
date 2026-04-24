<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appareil;
use App\Models\UserLog;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;


class AppareilController extends Controller{
    public function index(Request $request)
    {
        $query = Appareil::query();
 
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name',  'like', "%$s%")
                  ->orWhere('type',  'like', "%$s%")
                  ->orWhere('brand', 'like', "%$s%");
            });
        }
 
        if ($request->filled('status') && in_array($request->status, ['actif', 'inactif', 'maintenance'])) {
            $query->where('status', $request->status);
        }
 
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
 
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
 
        $appareils = $query->orderBy('name')->get();
 
        $types  = Appareil::whereNotNull('type')->distinct()->pluck('type')->sort()->values();
        $brands = Appareil::whereNotNull('brand')->distinct()->pluck('brand')->sort()->values();
 
        return view('appareil.rechercheAppareil', compact('appareils', 'types', 'brands'));
    }

    public function show($id){
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

    public function create(){
        //$this->adminOnly();
        return view('appareil.create');
    }

    public function store(Request $request){
        //$this->adminOnly();
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'nullable|string|max:100',
            'brand'       => 'nullable|string|max:100',
            'status'      => 'required|in:actif,inactif,maintenance',
            'description' => 'nullable|string',
            'room_id'     => 'nullable|exists:rooms,id',
            'image'       => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('appareils', 'public');
        }
        $data['added_by'] = auth()->id();
        $appareil = Appareil::create($data);
        return redirect()->route('appareil.show',$appareil->id)
             ->with('success','Appareil "'. $appareil->name . '" crée avec succès');
    }

    public function edit($id){
        //$this->adminOnly();
        $appareil = Appareil::findOrFail($id);
        return view('appareil.edit', compact('appareil'));
    }

    public function update(Request $request, $id){
        //$this->adminOnly();
        $appareil = Appareil::findOrFail($id);
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'nullable|string|max:100',
            'brand'       => 'nullable|string|max:100',
            'status'      => 'required|in:actif,inactif,maintenance',
            'description' => 'nullable|string',
            'room_id'     => 'nullable|exists:rooms,id',
            'image'       => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('image')) {
        if ($appareil->image) {
            \Storage::disk('public')->delete($appareil->image);
        }
        $data['image'] = $request->file('image')->store('appareils', 'public');
        }
        $appareil->update($data);
        return redirect()->route('appareil.show', $appareil->id)
                         ->with('success', 'Appareil mis à jour avec succès.');
    }

        public function destroy($id)
    {
        //$this->adminOnly();
        $appareil = Appareil::findOrFail($id);
        $name = $appareil->name;
        $appareil->delete();
        return redirect()->route('appareils.index')
                         ->with('success', 'Appareil « ' . $name . ' » supprimé.');
    }
 

    public function toggleStatus($id)
    {
        $this->adminOnly();
        $appareil = Appareil::findOrFail($id);
        $appareil->status = ($appareil->status === "actif") ? "inactif" : "actif";
        $appareil->save();
        return back()->with('success', '« ' . $appareil->name . ' » est maintenant ' . $appareil->status . '.');
    }

    private function adminOnly(){
        if(!auth()->check() || auth()->user()->role !== "admin") {
            abord(403, 'Action reservée aux administrateurs.');
        }
    }
}
