<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appareil;
use App\Models\UserLog;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;


class AppareilController extends Controller{
    
    //Fonction de recherche des appareils (avec filtres)
    public function index(Request $request)
    {
        $query = Appareil::with('room');
 
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name',  'like', "%$s%")
                  ->orWhere('type',  'like', "%$s%")
                  ->orWhere('brand', 'like', "%$s%")
                  ->orWhereHas('room', function ($q2) use ($s) {
                      $q2->where('name','like',"%$s%");
                    });
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

        if ($request->filled('room')){
            $query->where('room_id', $request->room);
        }

        $appareils = $query->orderBy('name')->get();

        $types  = Appareil::whereNotNull('type')->distinct()->pluck('type')->sort()->values();
        $brands = Appareil::whereNotNull('brand')->distinct()->pluck('brand')->sort()->values();
        $rooms = Room::orderBy('name')->get();

        $parConsommation = Appareil::whereNotNull('consumption')
            ->whereNotNull('usage_time')
            ->get()
            ->map(function ($a) {
                $a->wh_jour    = round($a->consumption * $a->usage_time / 60, 2);
                $a->wh_semaine = round($a->wh_jour * 7, 2);
                return $a;
            })
            ->sortByDesc('wh_jour')
            ->values();

        $tousLesAppareils = Appareil::with('room')->get()->map(function ($a) {
            $a->wh_jour = ($a->consumption && $a->usage_time)
                ? round($a->consumption * $a->usage_time / 60, 2)
                : null;
            return $a;
        });

        $inefficaces = $tousLesAppareils->filter(function ($a) {
            return $a->status === 'maintenance'
                || $a->status === 'inactif'
                || $a->delete_request_number >= 2
                || ($a->wh_jour !== null && $a->wh_jour > 500);
        })->values();

        return view('appareil.rechercheAppareil', compact('appareils', 'types', 'brands', 'rooms', 'parConsommation', 'inefficaces'));
    }

    //Barre de recherche des appareils
    public function rechercheAppareil(Request $request){
        $search = $request->input('search');

        $appareils = Appareil::with('room') // ✅ ICI
            ->where('name', 'like', '%' . $search . '%')
            ->orWhereHas('room', function ($query) use ($search) { // ✅ ICI
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->get();

        return view('appareils.index', compact('appareils'));
    }

    //Fonction pour montrer la page d'un des appareils
    public function show($id){
        // On récupère l'appareil ou on renvoie une erreur 404 s'il n'existe pas
        $appareil = Appareil::with('room')->findOrFail($id);
        $rooms = Room::all();
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

        return view('appareil.show', compact('appareil','rooms'));
    }

    //Fonction pour créer un appareil 
    public function create(){
        $this->adminOnly();
        $rooms = Room::all();
        return view('appareil.create',compact('rooms'));
    }

    //Fonction pour le stocker dans la base de donnée
    public function store(Request $request){
        $this->adminOnly();
        $rooms = Room::all();
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

    //Fonction pour éditer les informations d'un appareil
    public function edit($id){
        $this->adminOnly();
        $rooms = Room::all();
        $appareil = Appareil::findOrFail($id);
        return view('appareil.edit', compact('appareil','rooms'));
    }

    //Fonction pour mettre à jour dans la base de donnée
    public function update(Request $request, $id){
        $this->adminOnly();
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

    //Fonction pour supprimer un appareil
    public function destroy($id)
    {
        $this->adminOnly();
        $appareil = Appareil::findOrFail($id);
        $name = $appareil->name;
        $appareil->delete();
        return redirect()->route('appareil.index')
                         ->with('success', 'Appareil « ' . $name . ' » supprimé.');
    }
 
    //Fonction pour le voyant connecté/déconnecté
    public function toggleStatus($id)
    {
        $this->adminOnly();
        $appareil = Appareil::findOrFail($id);
        $appareil->status = ($appareil->status === "actif") ? "inactif" : "actif";
        $appareil->save();
        return back()->with('success', '« ' . $appareil->name . ' » est maintenant ' . $appareil->status . '.');
    }

    //Fonction pour les demandes de suppression (une par utilisateur)
    public function requestDelete($id){
        $appareil = Appareil::findOrFail($id);
        $userId   = auth()->id();
        $voters   = $appareil->delete_requested_by ?? [];

        if (in_array($userId, $voters)) {
            return back()->with('info', 'Vous avez déjà soumis une demande de suppression pour cet appareil.');
        }

        $voters[] = $userId;
        $appareil->delete_requested_by    = $voters;
        $appareil->delete_request_number  = count($voters);
        $appareil->save();

        return back()->with('success', 'Demande de suppression envoyée à un administrateur.');
    }

    //Fonction pour éditer la configuration
    public function editConfig($id){
        $this->adminOnly();
        $appareil = Appareil::findOrFail($id);
        return view('appareil.config', compact('appareil'));
    }


    //Fonction pour mettre à jour la configuration dans la base de donnée
    public function updateConfig(Request $request, $id){
        $this->adminOnly();
        $appareil = Appareil::findOrFail($id);
        $data = $request->validate([
            'start_hour'  => 'nullable|date_format:H:i',
            'end_hour'    => 'nullable|date_format:H:i|after:start_hour',
            'usage_time'  => 'nullable|integer|min:1|max:1440',
            'consumption' => 'nullable|integer|min:0|max:99999',
        ]);
        $appareil->update($data);
        return redirect()->route('appareil.show', $appareil->id)
                        ->with('success', 'Configuration mise à jour.');
    }

    //Fonction pour exporter en csv
    public function exportCSV($id)
{
    $appareil = Appareil::findOrFail($id);

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $appareil->name . '.csv"',
    ];

    $callback = function() use ($appareil) {
        $file = fopen('php://output', 'w');
        
        fputcsv($file, ['ID', 'Nom', 'Type', 'Marque', 'Statut', 'Description']);
        fputcsv($file, [
            $appareil->id,
            $appareil->name,
            $appareil->type,
            $appareil->brand,
            $appareil->status,
            $appareil->description,
        ]);

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
    //Fonction pour vérifier que la personne est un admin
    private function adminOnly(){
        if(!auth()->check() || auth()->user()->role !== "admin") {
            abort(403, 'Action reservée aux administrateurs.');
        }
    }
}
