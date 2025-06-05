<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\DestinationImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Traits\FlashMessage;

class DestinationController extends Controller
{
    use FlashMessage;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $destination = Destination::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'address' => $request->address,
        ]);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('destinations', 'public');
                DestinationImage::create([
                    'destination_id' => $destination->id,
                    'image_path' => $path
                ]);
            }
        }

        $this->flashSuccess('Điểm đến đã được tạo thành công.');
        return redirect()->route('admin.destinations.index');
    }

    public function show(Destination $destination)
    {
        $destination->load('images');
        return view('admin.destinations.show', compact('destination'));
    }

    public function edit(Destination $destination)
    {
        $destination->load('images');
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $destination->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'address' => $request->address,
        ]);

        // Handle new images
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('destinations', 'public');
                DestinationImage::create([
                    'destination_id' => $destination->id,
                    'image_path' => $path
                ]);
            }
        }

        $this->flashSuccess('Điểm đến đã được cập nhật thành công.');
        return redirect()->route('admin.destinations.index');
    }

    public function destroy(Destination $destination)
    {
        // Delete associated images from storage
        foreach ($destination->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Delete destination
        $destination->delete();

        $this->flashSuccess('Điểm đến đã được xóa thành công.');
        return redirect()->route('admin.destinations.index');
    }

    public function deleteImage(DestinationImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['success' => true]);
    }
}
