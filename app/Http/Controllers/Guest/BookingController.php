<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Get all bookings for the user, separated into current and past based on status
        $currentBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->with(['hotel', 'bookingItems.room'])
            ->orderBy('check_in_date', 'desc')
            ->get();

        $pastBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'checked_out', 'cancelled'])
            ->with(['hotel', 'bookingItems.room'])
            ->orderBy('check_out_date', 'desc')
            ->get();

        return view('guest.bookings.index', [
            'currentBookings' => $currentBookings,
            'pastBookings' => $pastBookings,
        ]);
    }

    public function create(Room $room): View
    {
        $room->load('hotel');

        return view('guest.bookings.create', [
            'room' => $room,
        ]);
    }

    public function store(Request $request, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'special_requests' => 'nullable|string|max:500',
        ], [
            'check_in_date.after' => 'Check-in date must be in the future',
            'check_out_date.after' => 'Check-out date must be after check-in date',
        ]);

        $checkInDate = new \DateTime($validated['check_in_date']);
        $checkOutDate = new \DateTime($validated['check_out_date']);
        $nights = $checkOutDate->diff($checkInDate)->days;
        $totalAmount = $nights * $room->price_per_night;

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'hotel_id' => $room->hotel_id,
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'total_amount' => $totalAmount,
            'special_requests' => $validated['special_requests'] ?? null,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Create booking item for the room
        $booking->bookingItems()->create([
            'room_id' => $room->id,
            'quantity' => $nights,
            'price_per_night' => $room->price_per_night,
        ]);

        return redirect()->route('guest.bookings.confirmation', $booking)
            ->with('success', 'Booking created! Please complete payment.');
    }

    public function confirmation(Booking $booking): View
    {
        // Ensure the booking belongs to the logged-in user
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $booking->load('hotel', 'bookingItems.room');

        return view('guest.bookings.confirmation', [
            'booking' => $booking,
        ]);
    }
}
