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
    public function index(Request $request)
    {
        $query = Booking::with(['tour', 'tourDeparture.guide'])
            ->orderBy('created_at', 'desc');

        // Filter by tour
        if ($request->filled('tour')) {
            $query->where('tour_id', $request->tour);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('start_date', '<=', $request->end_date);
        }

        $bookings = $query->paginate(10);
        $tours = Tour::all();

        return view('admin.bookings.index', compact('bookings', 'tours'));
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
        if ($request->status === 'cancelled') {
            $this->cancel($booking);
        }
        if ($request->status === 'pending') {
            $booking->tour_departure_id = null;
        }
        $booking->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function updatePaymentStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid,refunded',
            'payment_method' => 'required_if:payment_status,paid',
        ]);

        $booking->payment_status = $request->payment_status;
        $booking->payment_method = $request->payment_method;
        $booking->transaction_id = $request->transaction_id;
        $booking->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thanh toán thành công!');
    }

    public function showConfirmForm(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Không thể xác nhận đơn đặt tour này.');
        }

        // Lấy tất cả tour departure cho ngày này
        $departures = TourDeparture::where('tour_id', $booking->tour_id)
            ->where('departure_date', $booking->start_date)
            ->with(['guide', 'bookings' => function ($query) {
                $query->where('status', '!=', 'cancelled');
            }])
            ->get();

        return view('admin.bookings.confirm', compact('booking', 'departures'));
    }

    public function confirm(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Không thể xác nhận đơn đặt tour này.');
        }

        try {
            DB::beginTransaction();

            if ($request->input('action') === 'use_existing') {
                // Sử dụng chuyến đi hiện có
                $departure = TourDeparture::findOrFail($request->input('departure_id'));

                // Cập nhật booking với tour_departure_id
                $booking->update([
                    'tour_departure_id' => $departure->id,
                    'status' => 'confirmed'
                ]);

                DB::commit();
                return redirect()->route('admin.bookings.index')
                    ->with('success', 'Đã xác nhận đơn đặt tour và gắn với chuyến đi hiện có.');
            } else {
                // Tạo chuyến đi mới
                $departure = TourDeparture::create([
                    'tour_id' => $booking->tour_id,
                    'departure_date' => $booking->start_date,
                    'status' => 'pending'
                ]);

                // Cập nhật booking với tour_departure_id mới
                $booking->update([
                    'tour_departure_id' => $departure->id,
                    'status' => 'confirmed'
                ]);

                DB::commit();
                return redirect()->route('admin.bookings.index')
                    ->with('success', 'Đã xác nhận đơn đặt tour và tạo chuyến đi mới.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xác nhận đơn đặt tour: ' . $e->getMessage());
        }
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Đơn đặt tour này đã bị hủy.');
        }

        $booking->update([
            'status' => 'cancelled',
            'tour_departure_id' => null
        ]);

        return redirect()->back()->with('success', 'Đã hủy đơn đặt tour thành công.');
    }
}
