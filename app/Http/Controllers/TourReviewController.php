<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TourReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tour;

class TourReviewController extends Controller
{
    
    public function showReviewForm(Booking $booking)
    {
        // Kiểm tra xem booking đã hoàn thành chưa
        if ($booking->status !== 'completed') {
            return redirect()->route('home')->with('error', 'Chuyến đi chưa hoàn thành.');
        }

        // Kiểm tra xem đã đánh giá chưa
        if ($booking->review) {
            return redirect()->route('home')->with('info', 'Bạn đã đánh giá chuyến đi này.');
        }

        return view('tours.review', compact('booking'));
    }

    public function submitReview(Request $request, Booking $booking)
    {
        // Kiểm tra xem booking đã hoàn thành chưa
        if ($booking->status !== 'completed') {
            return redirect()->route('home')->with('error', 'Chuyến đi chưa hoàn thành.');
        }

        // Kiểm tra xem đã đánh giá chưa
        if ($booking->review) {
            return redirect()->route('home')->with('info', 'Bạn đã đánh giá chuyến đi này.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // Tạo đánh giá mới
        $review = new TourReview([
            'booking_id' => $booking->id,
            'tour_id' => $booking->tour_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_verified' => false
        ]);

        $review->save();

        return redirect()->route('profile.my-tours')->with('success', 'Cảm ơn bạn đã đánh giá chuyến đi!');
    }

    public function toggleVisibility(TourReview $review)
    {
        
        $review->is_verified = !$review->is_verified;
        $review->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái hiển thị đánh giá thành công.');
    }

    public function index()
    {
        $query = TourReview::with(['tour', 'booking']);

        if (request('tour_id')) {
            $query->where('tour_id', request('tour_id'));
        }
        $tours = Tour
        ::all();
        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.reviews.index', compact('reviews', 'tours'));
    }
} 