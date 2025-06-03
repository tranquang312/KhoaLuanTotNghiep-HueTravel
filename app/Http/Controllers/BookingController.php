<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'people' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'start_date' => 'required|date|after:today',
            'note' => 'nullable|string',
        ]);

        $tour = Tour::findOrFail($request->tour_id);
        $totalPrice = ($request->people + $request->children * 0.7) * $tour->price;

        // Kiểm tra số tiền tối thiểu (50 cents = 12,500 VND)
        if ($totalPrice < 12500) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['total_price' => 'Số tiền thanh toán phải lớn hơn 12,500 VND (50 cents)']);
        }

        $booking = new Booking();
        $booking->tour_id = $request->tour_id;
        $booking->name = $request->name;
        $booking->email = $request->email;
        $booking->phone = $request->phone;
        $booking->people = $request->people;
        $booking->children = $request->children;
        $booking->start_date = $request->start_date;
        $booking->note = $request->note;
        $booking->status = 'pending';
        $booking->total_price = $totalPrice;

        if (Auth::check()) {
            $booking->user_id = Auth::id();
        }

        $booking->save();

        return redirect()->route('payment.show', $booking)->with('success', 'Đặt tour thành công! Vui lòng thanh toán để hoàn tất đặt tour.');
    }

    public function cancel(Booking $booking)
    {
        // Kiểm tra xem người dùng có quyền hủy booking này không
        if (Auth::id() !== $booking->user_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền hủy đơn đặt tour này.');
        }

        // Kiểm tra trạng thái booking
        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Đơn đặt tour này đã bị hủy.');
        }

        if ($booking->status === 'completed') {
            return redirect()->back()->with('error', 'Không thể hủy tour đã hoàn thành.');
        }

        // Cập nhật trạng thái booking
        $booking->update([
            'status' => 'cancelled'
        ]);

        return redirect()->back()->with('success', 'Đã hủy đơn đặt tour thành công.');
    }
}
