<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourDeparture;
use Illuminate\Http\Request;

class TourDepartureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departures = TourDeparture::with('tour')->latest()->paginate(10);
        return view('admin.tour-departures.index', compact('departures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tours = Tour::all();
        return view('admin.tour-departures.create', compact('tours'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after:departure_date',
            'available_seats' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        TourDeparture::create($validated);

        return redirect()->route('admin.tour-departures.index')
            ->with('success', 'Chuyến đi đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TourDeparture $tourDeparture)
    {
        $tours = Tour::all();
        return view('admin.tour-departures.edit', compact('tourDeparture', 'tours'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TourDeparture $tourDeparture)
    {
        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after:departure_date',
            'available_seats' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $tourDeparture->update($validated);

        return redirect()->route('admin.tour-departures.index')
            ->with('success', 'Chuyến đi đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TourDeparture $tourDeparture)
    {
        $tourDeparture->delete();

        return redirect()->route('admin.tour-departures.index')
            ->with('success', 'Chuyến đi đã được xóa thành công.');
    }
}
