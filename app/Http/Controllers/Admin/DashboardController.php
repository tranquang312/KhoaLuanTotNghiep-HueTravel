<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total tours
        $totalTours = Tour::count();

        // Get new orders (bookings with pending status)
        $newOrders = Booking::where('status', 'pending')->count();

        // Get total users
        $totalUsers = User::count();

        // Get recent orders with related data
        $recentOrders = Booking::with(['tour', 'user'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($booking) {
                return (object)[
                    'id' => $booking->id,
                    'customer_name' => $booking->name,
                    'tour_name' => $booking->tour->name,
                    'total_amount' => $booking->total_price,
                    'status' => $booking->status
                ];
            });

        // Get monthly revenue data for the chart
        $monthlyRevenue = Booking::where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prepare chart data
        $chartData = array_fill(0, 12, 0); // Initialize array with zeros for all months
        foreach ($monthlyRevenue as $revenue) {
            $chartData[$revenue->month - 1] = $revenue->total;
        }

        return view('admin.dashboard', compact(
            'totalTours',
            'newOrders',
            'totalUsers',
            'recentOrders',
            'chartData'
        ));
    }
}
