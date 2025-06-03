<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Hiển thị danh sách điểm đến
     */
    public function index()
    {
        $destinations = Destination::with(['images', 'tours'])->paginate(9);
        return view('destinations.index', compact('destinations'));
    }

    /**
     * Hiển thị chi tiết điểm đến
     */
    public function show(Destination $destination)
    {
        $destination->load(['images', 'tours']);
        return view('destinations.show', compact('destination'));
    }
}
