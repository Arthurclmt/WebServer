<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    //Fonction pour chercher les news
    public function index()
    {
        $news = News::latest()->paginate(9);
        return view('news.index', compact('news'));
    }

    //Fonction pour créer une news
    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('news.create');
    }

    //Fonction pour stocker une news dans la base de donnée
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
        }

        News::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'type' => 'news',
        ]);

        return redirect()->route('news.index');
    }
}
