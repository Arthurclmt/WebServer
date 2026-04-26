<?php

namespace App\Http\Controllers;

use App\Models\Appareil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatsController extends Controller
{
    public function index()
    {

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès interdit.');
        }


        $totalConsommation = Appareil::sum('consumption');
        $avgUsage = Appareil::avg('usage_time');
        
        $inefficientDevices = Appareil::where('consumption', '>', 100)
                                      ->where('usage_time', '<', 2)
                                      ->get();

        $allDevices = Appareil::all(); 

      
        return view('stats.index', compact('totalConsommation', 'avgUsage', 'inefficientDevices', 'allDevices'));
    }
}