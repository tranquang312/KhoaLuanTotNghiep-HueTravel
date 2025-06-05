<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $bookings = $request->user()->bookings()
            ->with(['tour', 'tourDeparture'])
            ->latest()
            ->paginate(10);

        return view('profile.edit', [
            'user' => $request->user(),
            'bookings' => $bookings
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            $oldAvatar = User::find($request->user()->id)->avatar;
            if ($request->user()->avatar && $oldAvatar != null) {
                Storage::disk('public')->delete($oldAvatar);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $request->user()->avatar = $path;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Display user's booked tours.
     */
    public function myTours(Request $request): View
    {
        $bookings = $request->user()->bookings()
            ->with(['tour', 'tourDeparture'])
            ->latest()
            ->paginate(10);

        return view('profile.my-tours', [
            'bookings' => $bookings
        ]);
    }

    public function posts(Request $request)
    {
        $query = auth()->user()->posts()
            ->with(['destination', 'likes'])
            ->withCount('likes');

        if ($request->has('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }

        $posts = $query->latest()->paginate(4);
        $destinations = auth()->user()->posts()
            ->with('destination')
            ->get()
            ->pluck('destination')
            ->unique('id')
            ->values();

        return view('profile.posts', compact('posts', 'destinations'));
    }
}
