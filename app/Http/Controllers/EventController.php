<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\News;

class EventController extends Controller{
    public function index(){
        $events = Event::orderBy('event_date', 'asc')->get();
        return view('events.index', compact('events'));
    }

    // Page des evénement en eux même
    public function show($slug){
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('events.show', compact('event'));
    }

    public function create(){
    // Vérifier que c'est un admin
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        return view('events.create');
    }

    public function store(Request $request){
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $data = $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'required|string',
        'content'     => 'nullable|string',
        'event_date'  => 'required|date',
        'image'       => 'nullable|image|max:2048',
        ]);
        // Générer le slug
        $data['slug'] = Str::slug($request->title);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }
        Event::create($data);
        News::create([
        'title' => $event->title,
        'content' => $event->description,
        'image' => $event->image,
        'type' => 'event',
        'event_id' => $event->id,
        ]);
        return redirect()->route('events.index')->with('success', 'Événement créé !');
    }
}
