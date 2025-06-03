<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Tour;
use Illuminate\Support\Facades\DB;
use App\Models\TourDeparture;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['tour', 'user'])
            ->latest()
            ->paginate(5);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['tour', 'user']);
        $guides = User::role('guide')->get();
        return view('admin.bookings.show', compact('booking', 'guides'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $booking->status = $request->status;
        $booking->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function updatePaymentStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid,refunded',
            'payment_method' => 'required_if:payment_status,paid',
            'transaction_id' => 'required_if:payment_status,paid'
        ]);

        $booking->payment_status = $request->payment_status;
        $booking->payment_method = $request->payment_method;
        $booking->transaction_id = $request->transaction_id;
        $booking->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thanh toán thành công!');
    }

    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Không thể xác nhận đơn đặt tour này.');
        }

        $booking->update([
            'status' => 'confirmed'
        ]);

        // Gửi email thông báo cho khách hàng
        // TODO: Implement email notification

        return redirect()->back()->with('success', 'Đã xác nhận đơn đặt tour thành công.');
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Đơn đặt tour này đã bị hủy.');
        }

        $booking->update([
            'status' => 'cancelled'
        ]);

        return redirect()->back()->with('success', 'Đã hủy đơn đặt tour thành công.');
    }

    public function upcoming()
    {

        $currentDate = Carbon::now()->format('Y-m-d');
        $upcomingBookings = Booking::select(
            'tour_id',
            'start_date',
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(people) as total_adults'),
            DB::raw('SUM(children) as total_children'),
            DB::raw('SUM(total_price) as total_revenue')
        )
            ->where('status', 'confirmed')
            ->where('start_date', '>=', $currentDate)
            ->groupBy('tour_id', 'start_date')
            ->with(['tour', 'tourDeparture' => function ($query) {
                $query->with('guide');
            }])
            ->orderBy('start_date')
            ->paginate(10);

        $guides = User::role('guide')->get();

        return view('admin.bookings.upcoming', compact('upcomingBookings', 'guides'));
    }

    public function tourDetails($tourId, $date)
    {
        $bookings = Booking::where('tour_id', $tourId)
            ->where('start_date', $date)
            ->where('status', '!=', 'cancelled')
            ->with(['tour', 'user'])
            ->get();

        $tour = Tour::findOrFail($tourId);

        return view('admin.bookings.tour-details', compact('bookings', 'tour', 'date'));
    }

    public function assignGuide(Request $request, Booking $booking)
    {
        $request->validate([
            'guide_id' => 'required|exists:users,id',
            'departure_date' => 'required|date|after_or_equal:today',
        ]);

        // Tạo hoặc cập nhật tour departure
        $departure = TourDeparture::updateOrCreate(
            [
                'tour_id' => $booking->tour_id,
                'departure_date' => $request->departure_date,
                'guide_id' => $request->guide_id
            ],
            [
                'status' => 'active'
            ]
        );

        // Cập nhật booking với departure_id
        $booking->update([
            'departure_id' => $departure->id,
            'status' => 'confirmed'
        ]);

        return redirect()->back()->with('success', 'Đã phân công guide và cập nhật thông tin chuyến đi thành công!');
    }

    public function assignGuideByDate(Request $request, $tourId, $date)
    {
        $request->validate([
            'guide_id' => 'required|exists:users,id'
        ]);

        // Tạo hoặc cập nhật tour departure
        $departure = TourDeparture::updateOrCreate(
            [
                'tour_id' => $tourId,
                'departure_date' => $date,
                'guide_id' => $request->guide_id
            ],
            [
                'status' => 'active'
            ]
        );

        // Cập nhật tất cả booking của tour này trong ngày này
        Booking::where('tour_id', $tourId)
            ->where('start_date', $date)
            ->where('status', '!=', 'cancelled')
            ->update(['departure_id' => $departure->id]);

        return redirect()->back()->with('success', 'Đã phân công hướng dẫn viên thành công!');
    }

    public function active()
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $activeDepartures = \App\Models\TourDeparture::with(['tour', 'guide', 'bookings' => function($query) {
            $query->where('status', '!=', 'completed');
        }])
            ->where('departure_date', '<=', $currentDate)
            ->where('status', '!=', 'completed')
            ->latest('departure_date')
            ->paginate(10);

        return view('admin.bookings.active', compact('activeDepartures'));
    }

    public function markCompleted(Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Không thể đánh dấu tour này hoàn tất.');
        }

        $booking->update([
            'status' => 'completed'
        ]);

        return redirect()->back()->with('success', 'Đã đánh dấu tour hoàn tất thành công.');
    }

    
}
