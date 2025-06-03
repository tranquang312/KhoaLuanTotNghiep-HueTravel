<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\DestinationImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::with('images')->latest()->paginate(10);
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $destination = Destination::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = Str::slug($destination->name) . '-' . time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('destinations', $imageName, 'public');

                DestinationImage::create([
                    'destination_id' => $destination->id,
                    'image_path' => 'destinations/' . $imageName
                ]);
            }
        }

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Điểm đến đã được thêm thành công.');
    }

    public function edit(Destination $destination)
    {
        // Lấy tất cả ảnh của điểm đến
        $allImages = DB::table('destination_images')
            ->where('destination_id', $destination->id)
            ->get();

        return view('admin.destinations.edit', compact('destination', 'allImages'));
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $destination->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
        ]);

        if ($destination->images()->count() > 0) {
            $destination->images()->delete();
            //Storage::disk('public')->deleteDirectory('destinations');
        }

        // update ảnh mới
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = Str::slug($destination->name) . '-' . time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('destinations', $imageName, 'public');

                DestinationImage::create([
                    'destination_id' => $destination->id,
                    'image_path' => 'destinations/' . $imageName
                ]);
            }
        }

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Điểm đến đã được cập nhật thành công.');
    }


    public function destroy(Destination $destination)
    {
        // Xóa tất cả ảnh
        foreach ($destination->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $destination->delete();

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Điểm đến đã được xóa thành công.');
    }
}
