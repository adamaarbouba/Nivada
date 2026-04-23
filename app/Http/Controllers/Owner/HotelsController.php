<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelsController extends Controller
{
    /**
     * Display all hotels owned by the authenticated owner
     */
    public function index(): View
    {
        $user = Auth::user();

        // Get all hotels owned by this owner with relationships
        $hotels = $user->ownedHotels()
            ->with('rooms')
            ->latest('created_at')
            ->paginate(10);

        return view('owner.hotels.index', compact('hotels'));
    }

    /**
     * Display a specific hotel with details and employees (view-only)
     */
    public function show(Hotel $hotel): View
    {
        // Ensure the hotel belongs to the authenticated owner
        if ($hotel->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Load staff and receptionists, excluding banned users
        $hotel->load([
            'staff' => function ($query) {
                $query->whereNull('banned_at');
            },
            'receptionists' => function ($query) {
                $query->whereHas('user', function ($userQuery) {
                    $userQuery->whereNull('banned_at');
                });
            },
            'rooms'
        ]);

        return view('owner.hotels.show', compact('hotel'));
    }

    /**
     * Display hotel management interface for editing rooms
     */
    public function manage(Hotel $hotel): View
    {
        // Ensure the hotel belongs to the authenticated owner
        if ($hotel->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $hotel->load('rooms');

        return view('owner.hotels.manage', compact('hotel'));
    }

    /**
     * Get rooms for a specific hotel (used for modal/form)
     */
    public function getRooms(Hotel $hotel)
    {
        // Ensure the hotel belongs to the authenticated owner
        if ($hotel->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $rooms = $hotel->rooms()->get();

        return response()->json([
            'hotel' => $hotel,
            'rooms' => $rooms,
        ]);
    }

    /**
     * Update room status
     */
    public function updateRoomStatus(Hotel $hotel, Request $request)
    {
        // Ensure the hotel belongs to the authenticated owner
        if ($hotel->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'status' => 'required|in:Available,Occupied,Maintenance,Cleaning,Disabled,Inspection',
        ]);

        $room = $hotel->rooms()->find($validated['room_id']);

        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        $room->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Room status updated successfully',
            'room' => $room,
        ]);
    }

    /**
     * Add a new room to the hotel
     */
    public function addRoom(Hotel $hotel, Request $request)
    {
        // Ensure the hotel belongs to the authenticated owner
        if ($hotel->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'room_number' => 'required|string',
            'type' => 'required|in:single,double,suite',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
        ]);

        $room = $hotel->rooms()->create([
            'room_number' => $validated['room_number'],
            'room_type' => $validated['type'],
            'price_per_night' => $validated['price'],
            'capacity' => $validated['capacity'],
            'status' => 'Available',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Room added successfully',
            'room' => $room,
        ]);
    }

    /**
     * Update a room
     */
    public function updateRoom(Hotel $hotel, Room $room, Request $request)
    {
        // Ensure the hotel belongs to the authenticated owner
        if ($hotel->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Ensure the room belongs to this hotel
        if ($room->hotel_id !== $hotel->id) {
            abort(403, 'Unauthorized');
        }

        // Check if room is occupied - cannot modify occupied rooms
        if ($room->status === 'Occupied') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot modify an occupied room. Change its status to Available or Maintenance first.',
            ], 422);
        }

        $validated = $request->validate([
            'room_number' => 'required|string',
            'type' => 'required|in:single,double,suite',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
        ]);

        $room->update([
            'room_number' => $validated['room_number'],
            'room_type' => $validated['type'],
            'price_per_night' => $validated['price'],
            'capacity' => $validated['capacity'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Room updated successfully',
            'room' => $room,
        ]);
    }

    /**
     * Disable a room (instead of deleting)
     */
    public function deleteRoom(Hotel $hotel, Room $room)
    {
        // Ensure the hotel belongs to the authenticated owner
        if ($hotel->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Ensure the room belongs to this hotel
        if ($room->hotel_id !== $hotel->id) {
            abort(403, 'Unauthorized');
        }

        // Check if room is occupied - cannot disable occupied rooms
        if ($room->status === 'Occupied') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot disable an occupied room. Change its status to Available or Maintenance first.',
            ], 422);
        }

        // Change status to Disabled instead of deleting
        $room->update(['status' => 'Disabled']);

        return response()->json([
            'success' => true,
            'message' => 'Room disabled successfully',
        ]);
    }

    /**
     * Update hotel name and default hourly wage only
     */
    public function updateHotel(Hotel $hotel, Request $request)
    {
        // Ensure the hotel belongs to the authenticated owner
        if ($hotel->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'default_hourly_wage' => 'nullable|numeric|min:0',
        ]);

        $hotel->update([
            'name' => $validated['name'],
            'default_hourly_wage' => $validated['default_hourly_wage'],
        ]);

        return redirect()->route('owner.hotels.show', $hotel)
            ->with('success', 'Hotel updated successfully.');
    }
}
