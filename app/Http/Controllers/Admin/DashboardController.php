<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Booking;
use App\Models\HotelRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with statistics
     */
    public function index(): View
    {
        // Get statistics
        $stats = [
            'totalHotels' => Hotel::count(),
            'totalUsers' => User::count(),
            'totalBookings' => Booking::count(),
            'totalRevenue' => $this->calculateRevenue(),
            'pendingRequests' => HotelRequest::where('status', 'pending')->count(),
            'approvedRequests' => HotelRequest::where('status', 'approved')->count(),
            'rejectedRequests' => HotelRequest::where('status', 'rejected')->count(),
        ];

        // Get recent users (exclude admin)
        $systemUsers = User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('slug', '!=', 'admin');
            })
            ->latest('created_at')
            ->limit(10)
            ->get();

        // Get recent hotel requests
        $recentRequests = HotelRequest::with('owner', 'reviewer')
            ->latest('created_at')
            ->limit(5)
            ->get();

        // Get hotel request breakdown
        $requestStats = [
            'pending' => HotelRequest::where('status', 'pending')->count(),
            'approved' => HotelRequest::where('status', 'approved')->count(),
            'rejected' => HotelRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'systemUsers', 'recentRequests', 'requestStats'));
    }

    /**
     * Calculate total revenue from completed bookings with payment received
     */
    private function calculateRevenue(): float
    {
        return Booking::where('status', '!=', 'cancelled')
            ->whereIn('payment_status', ['paid', 'partial'])
            ->sum('total_amount') ?? 0;
    }
}
