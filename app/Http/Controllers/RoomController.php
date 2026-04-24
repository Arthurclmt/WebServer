<?php
namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount('appareils')->orderBy('name')->get();
        return view('rooms.index', compact('rooms'));
    }

    public function store(Request $request)
    {
        $this->adminOnly();
        $request->validate(['name' => 'required|string|max:100']);
        Room::create($request->only('name', 'capacity', 'description'));
        return back()->with('success', 'Pièce créée.');
    }

    public function destroy($id)
    {
        $this->adminOnly();
        Room::findOrFail($id)->delete();
        return back()->with('success', 'Pièce supprimée.');
    }

    private function adminOnly()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }
    }
}