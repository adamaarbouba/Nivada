<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Current bookings (pending or confirmed, check-in is today or in future)
        $currentBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->where('check_in_date', '>=', today())
            ->with('hotel', 'bookingItems.room')
            ->orderBy('check_in_date', 'asc')
            ->get();

        // Past bookings (completed or checked out)
        $pastBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'checked_out', 'cancelled'])
            ->where('check_out_date', '<=', today())
            ->with('hotel', 'bookingItems.room')
            ->orderBy('check_out_date', 'desc')
            ->get();

        // Guest reviews count
        $reviewsCount = Review::where('user_id', $user->id)->count();

        return view('guest.dashboard', [
            'currentBookingsCount' => $currentBookings->count(),
            'pastBookingsCount' => $pastBookings->count(),
            'reviewsCount' => $reviewsCount,
            'currentBookings' => $currentBookings,
            'pastBookings' => $pastBookings,
        ]);
    }
}
