<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Tour;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy 3 tour mới nhất với eager loading images
        $tours = Tour::with('images')->latest()->take(3)->get();
        
        // Lấy 3 điểm đến mới nhất với eager loading images
        $destinations = Destination::with('images')->latest()->take(4)->get();
        
        // Debug để xem dữ liệu
        // dd($tours, $destinations);
        
        return view('home', compact('destinations', 'tours'));
    }
}
