<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourImage;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::with(['destinations', 'images'])->latest()->paginate(10);
        return view('admin.tours.index', compact('tours'));
    }

    public function create()
    {
        $destinations = Destination::all();
        return view('admin.tours.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'itinerary' => 'required|string',
            'destinations' => 'required|array',
            'destinations.*' => 'exists:destinations,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $tour = Tour::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'itinerary' => $request->itinerary,
        ]);
        //dd($request->destinations);
        // Attach destinations
        $tour->destinations()->attach($request->destinations);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('tours', 'public');
                TourImage::create([
                    'tour_id' => $tour->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('admin.tours.index')
            ->with('success', 'Tour created successfully.');
    }

    public function show(Tour $tour)
    {
        $tour->load(['destinations', 'images']);
        return view('admin.tours.show', compact('tour'));
    }

    public function edit(Tour $tour)
    {
        $destinations = Destination::all();
        $tour->load(['destinations', 'images']);
        return view('admin.tours.edit', compact('tour', 'destinations'));
    }

    public function update(Request $request, Tour $tour)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'itinerary' => 'required|string',
            'destinations' => 'required|array',
            'destinations.*' => 'exists:destinations,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $tour->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'itinerary' => $request->itinerary,
        ]);

        // Sync destinations
        $tour->destinations()->sync($request->destinations);

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('tours', 'public');
                TourImage::create([
                    'tour_id' => $tour->id,
                    'image_path' => $path
                ]);
            }
        }
        // Handle new images
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('tours', 'public');
                TourImage::create([
                    'tour_id' => $tour->id,
                    'image_path' => $path
                ]);
            }
        }


        return redirect()->route('admin.tours.index')
            ->with('success', 'Tour updated successfully.');
    }

    public function destroy(Tour $tour)
    {
        // Delete associated images from storage
        foreach ($tour->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Detach destinations
        $tour->destinations()->detach();

        // Delete tour
        $tour->delete();

        return redirect()->route('admin.tours.index')
            ->with('success', 'Tour deleted successfully.');
    }

    public function deleteImage(TourImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}
