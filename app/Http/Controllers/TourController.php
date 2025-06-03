<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Destination;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $query = Tour::with(['images', 'destinations']);

        // Filter by price range
        if ($request->has('price_range')) {
            $range = explode('-', $request->price_range);
            if (count($range) === 2) {
                $query->whereBetween('price', [$range[0], $range[1]]);
            }
        }

        // Filter by destinations
        if ($request->has('destinations')) {
            $query->whereHas('destinations', function ($q) use ($request) {
                $q->whereIn('destinations.id', $request->destinations);
            });
        }

        $tours = $query->latest()->paginate(9);
        $destinations = Destination::all();

        return view('tours.index', compact('tours', 'destinations'));
    }

    public function show(Tour $tour)
    {
        $tour->load(['images', 'destinations']);
        return view('tours.show', compact('tour'));
    }
}
