<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourDeparture;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\TourInfoMail;
use App\Mail\TourCompletionMail;

class TourDepartureController extends Controller
{
    public function index(Request $request)
    {
        $query = TourDeparture::with(['tour', 'guide', 'bookings'])
            ->orderBy('departure_date', 'asc');

        // Filter by date
        if ($request->has('filter')) {
            if ($request->filter === 'today') {
                $query->whereDate('departure_date', now()->toDateString());
            } elseif ($request->filter === 'next_7_days') {
                $query->whereBetween('departure_date', [
                    now()->startOfDay(),
                    now()->addDays(7)->endOfDay()
                ]);
            }
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->whereNull('guide_id');
            } elseif ($request->status === 'confirmed') {
                $query->whereNotNull('guide_id')
                    ->where('guide_status', 'confirmed');
            }
        }

        $departures = $query->paginate(10);

        // Get guides for assignment
        $guides = User::role('guide')
            ->withCount(['tourDepartures' => function ($query) {
                $query->whereDate('departure_date', now()->toDateString());
            }])
            ->get();

        return view('admin.tour-departures.index', compact('departures', 'guides'));
    }

    public function assignGuide(Request $request, TourDeparture $departure)
    {
        $request->validate([
            'guide_id' => 'required|exists:users,id',
        ]);
        try {
            DB::beginTransaction();

            // Cập nhật tour departure với hướng dẫn viên mới
            $departure->update([
                'guide_id' => $request->guide_id,
                'guide_status' => 'pending'
            ]);

            // Cập nhật trạng thái của tất cả booking liên quan
            $departure->bookings()->where('status', 'pending')->update(['status' => 'confirmed']);

            DB::commit();
            return redirect()->route('admin.tour-departures.index')
                ->with('success', 'Đã phân công hướng dẫn viên thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi phân công hướng dẫn viên: ' . $e->getMessage());
        }
    }

    public function guideConfirm(TourDeparture $departure)
    {
        // Kiểm tra xem người dùng hiện tại có phải là hướng dẫn viên được phân công không
        if (auth()->id() !== $departure->guide_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        try {
            DB::beginTransaction();
            
            $departure->update([
                'guide_status' => 'confirmed'
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Đã xác nhận nhận chuyến đi thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function guideReject(TourDeparture $departure)
    {
        // Kiểm tra xem người dùng hiện tại có phải là hướng dẫn viên được phân công không
        if (auth()->id() !== $departure->guide_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        try {
            DB::beginTransaction();
            
            $departure->update([
                'guide_status' => 'rejected',
                'guide_id' => null
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Đã từ chối chuyến đi thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function showBookings(TourDeparture $departure)
    {
        // Kiểm tra xem người dùng hiện tại có phải là hướng dẫn viên được phân công không
        if (auth()->id() !== $departure->guide_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền xem thông tin này.');
        }

        $bookings = $departure->bookings()->with(['user', 'tour'])->get();
        return view('admin.tour-departures.bookings', compact('departure', 'bookings'));
    }

    public function sendTourInfo(TourDeparture $departure)
    {
        // Kiểm tra xem người dùng hiện tại có phải là hướng dẫn viên được phân công không
        if (auth()->id() !== $departure->guide_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        try {
            $bookings = $departure->bookings()->with(['user', 'tour'])->get();
            
            foreach ($bookings as $booking) {
                // Gửi email thông tin chuyến đi cho từng khách hàng
                Mail::to($booking->email)->send(new TourInfoMail($booking, $departure));
            }

            return redirect()->back()->with('success', 'Đã gửi thông tin chuyến đi cho khách hàng thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi email: ' . $e->getMessage());
        }
    }

    public function completeTour(TourDeparture $departure)
    {
        // Kiểm tra xem người dùng hiện tại có phải là hướng dẫn viên được phân công không
        if (auth()->id() !== $departure->guide_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        try {
            DB::beginTransaction();

            // Cập nhật trạng thái chuyến đi
            $departure->update([
                'status' => 'completed'
            ]);

            // Cập nhật trạng thái các booking
            foreach ($departure->bookings as $booking) {
                $booking->update([
                    'status' => 'completed'
                ]);
            }

            // Gửi email cảm ơn và yêu cầu đánh giá cho từng khách hàng
            $bookings = $departure->bookings()->with(['user', 'tour'])->get();
            foreach ($bookings as $booking) {
                Mail::to($booking->email)->send(new TourCompletionMail($booking, $departure));
            }

            DB::commit();
            return redirect()->back()->with('success', 'Đã xác nhận hoàn thành chuyến đi và gửi email cảm ơn cho khách hàng.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function startTour(TourDeparture $departure)
    {
        // Kiểm tra xem tour đã có hướng dẫn viên và đã được xác nhận chưa
        if (!$departure->guide || $departure->guide_status !== 'confirmed') {
            return redirect()->back()->with('error', 'Tour chưa có hướng dẫn viên hoặc chưa được xác nhận.');
        }

        // Kiểm tra xem tour có đang ở trạng thái pending không
        if ($departure->status !== 'pending') {
            return redirect()->back()->with('error', 'Tour không thể khởi hành ở trạng thái hiện tại.');
        }

        // Cập nhật trạng thái tour thành active
        $departure->update([
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Tour đã được khởi hành thành công.');
    }
} 