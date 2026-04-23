<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\News;


class DashboardController extends Controller
{
    public function index(){
        $latestNews = News::latest()->take(3)->get();
        return view('dashboard', compact('latestNews'));
    }
}
