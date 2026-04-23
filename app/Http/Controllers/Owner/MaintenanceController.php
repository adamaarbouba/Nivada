<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\MaintenanceRequest;
use App\Models\Room;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Show maintenance dashboard for owner
     */
    public function index(): View
    {
        $user = auth()->user();

        // Get hotels owned by this user
        $hotels = Hotel::where('owner_id', $user->id)->get();
        $hotelIds = $hotels->pluck('id');

        // Get all maintenance requests for owner's hotels
        $maintenanceRequests = MaintenanceRequest::whereIn('hotel_id', $hotelIds)
            ->with(['room', 'hotel', 'inspector'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get rooms in Maintenance status
        $maintenanceRooms = Room::whereIn('hotel_id', $hotelIds)
            ->where('status', 'Maintenance')
            ->with('hotel')
            ->orderBy('room_number')
            ->get();

        return view('owner.maintenance.index', [
            'maintenanceRequests' => $maintenanceRequests,
            'maintenanceRooms' => $maintenanceRooms,
            'hotels' => $hotels,
        ]);
    }

    /**
     * Show maintenance details and transition form
     */
    public function show(MaintenanceRequest $maintenanceRequest): View
    {
        $user = auth()->user();

        // Verify owner owns this hotel
        $hotel = Hotel::where('id', $maintenanceRequest->hotel_id)
            ->where('owner_id', $user->id)
            ->firstOrFail();

        return view('owner.maintenance.show', [
            'maintenanceRequest' => $maintenanceRequest,
            'hotel' => $hotel,
        ]);
    }

    /**
     * Transition maintenance room to next status
     */
    public function transition(Request $request, MaintenanceRequest $maintenanceRequest): RedirectResponse
    {
        $user = auth()->user();

        // Verify owner owns this hotel
        $hotel = Hotel::where('id', $maintenanceRequest->hotel_id)
            ->where('owner_id', $user->id)
            ->firstOrFail();

        $validated = $request->validate([
            'next_status' => 'required|in:Cleaning,Available',
            'completion_notes' => 'nullable|string|max:1000',
        ]);

        $room = $maintenanceRequest->room;
        $newStatus = $validated['next_status'];
        $completionNotes = $validated['completion_notes'] ?? '';

        // Update maintenance request
        $maintenanceRequest->update([
            'status' => 'completed',
            'completion_notes' => $completionNotes,
            'completed_at' => now(),
        ]);

        // Update room status
        $room->update(['status' => $newStatus]);

        $message = 'Room ' . $room->room_number . ' moved to ' . $newStatus;
        if ($completionNotes) {
            $message .= '. Notes: ' . $completionNotes;
        }

        return redirect()->route('owner.maintenance.index')
            ->with('success', $message);
    }
}
