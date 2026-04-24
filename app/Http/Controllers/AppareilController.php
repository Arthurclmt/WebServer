<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appareil;
use App\Models\UserLog;
use App\Models\Room;


class AppareilController extends Controller{
    
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


        return view('appareil.rechercheAppareil', compact('appareils', 'types', 'brands','rooms'));
    }

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

    public function create(){
        $this->adminOnly();
        $rooms = Room::all();
        return view('appareil.create',compact('rooms'));
    }

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
        ]);
        $data['added_by'] = auth()->id();
        $appareil = Appareil::create($data);
        return redirect()->route('appareil.show',$appareil->id)
                         ->with('success','Appareil "'. $appareil->name . '" crée avec succès');
    }

    public function edit($id){
        $this->adminOnly();
        $rooms = Room::all();
        $appareil = Appareil::findOrFail($id);
        return view('appareil.edit', compact('appareil','rooms'));
    }

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
        ]);
        $appareil->update($data);
        return redirect()->route('appareil.show', $appareil->id)
                         ->with('success', 'Appareil mis à jour avec succès.');
    }

        public function destroy($id)
    {
        $this->adminOnly();
        $appareil = Appareil::findOrFail($id);
        $name = $appareil->name;
        $appareil->delete();
        return redirect()->route('appareil.index')
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

    public function editConfig($id){
        $this->adminOnly();
        $appareil = Appareil::findOrFail($id);
        return view('appareil.config', compact('appareil'));
    }

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


    private function adminOnly(){
        if(!auth()->check() || auth()->user()->role !== "admin") {
            abort(403, 'Action reservée aux administrateurs.');
        }
    }
}
