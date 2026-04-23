<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelReceptionist;
use App\Models\HotelStaff;
use App\Models\StaffApplication;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * List all staff for a specific hotel
     */
    public function index(Hotel $hotel): View
    {
        // Ensure the hotel belongs to the authenticated owner
        if ($hotel->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $hotel->load([
            'staff' => function ($query) {
                $query->whereNull('banned_at');
            },
            'receptionists.user' => function ($query) {
                $query->whereNull('banned_at');
            },
        ]);

        return view('owner.staff.index', compact('hotel'));
    }

    /**
     * View all staff applications for owner's hotels
     */
    public function applications(): View
    {
        $user = Auth::user();
        $hotelIds = Hotel::where('owner_id', $user->id)->pluck('id');

        $pendingApplications = StaffApplication::whereIn('hotel_id', $hotelIds)
            ->pending()
            ->with('user', 'hotel')
            ->orderBy('created_at', 'desc')
            ->get();

        $reviewedApplications = StaffApplication::whereIn('hotel_id', $hotelIds)
            ->whereIn('status', ['approved', 'rejected'])
            ->with('user', 'hotel', 'reviewer')
            ->orderBy('reviewed_at', 'desc')
            ->take(20)
            ->get();

        return view('owner.staff.applications', compact('pendingApplications', 'reviewedApplications'));
    }

    /**
     * Approve a staff application
     */
    public function approveApplication(Request $request, StaffApplication $staffApplication): RedirectResponse
    {
        $user = Auth::user();

        // Verify owner owns the hotel
        $hotel = Hotel::where('id', $staffApplication->hotel_id)
            ->where('owner_id', $user->id)
            ->firstOrFail();

        if ($staffApplication->status !== 'pending') {
            return back()->with('error', 'This application has already been processed.');
        }

        $validated = $request->validate([
            'hourly_rate' => 'required|numeric|min:0.01',
        ]);

        // For receptionists, check they don't already work at another hotel
        if ($staffApplication->role === 'receptionist') {
            $existingAssignment = HotelReceptionist::where('user_id', $staffApplication->user_id)->first();
            if ($existingAssignment) {
                return back()->with('error', 'This receptionist is already assigned to another hotel.');
            }
        }

        // Approve the application
        $staffApplication->update([
            'status' => 'approved',
            'hourly_rate' => $validated['hourly_rate'],
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
        ]);

        // Create the actual staff assignment
        if ($staffApplication->role === 'receptionist') {
            HotelReceptionist::create([
                'user_id' => $staffApplication->user_id,
                'hotel_id' => $hotel->id,
                'status' => 'active',
            ]);
        } else {
            // cleaner or inspector
            HotelStaff::create([
                'user_id' => $staffApplication->user_id,
                'hotel_id' => $hotel->id,
                'role' => $staffApplication->role,
                'hourly_rate' => $validated['hourly_rate'],
                'is_available' => true,
            ]);
        }

        return redirect()->route('owner.staff.applications')
            ->with('success', 'Application approved! ' . $staffApplication->user->name . ' is now assigned to ' . $hotel->name . '.');
    }

    /**
     * Reject a staff application
     */
    public function rejectApplication(Request $request, StaffApplication $staffApplication): RedirectResponse
    {
        $user = Auth::user();

        // Verify owner owns the hotel
        Hotel::where('id', $staffApplication->hotel_id)
            ->where('owner_id', $user->id)
            ->firstOrFail();

        if ($staffApplication->status !== 'pending') {
            return back()->with('error', 'This application has already been processed.');
        }

        $staffApplication->update([
            'status' => 'rejected',
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
        ]);

        return redirect()->route('owner.staff.applications')
            ->with('success', 'Application rejected.');
    }

    /**
     * Remove a staff member from a hotel
     */
    public function removeStaff(Hotel $hotel, User $user): RedirectResponse
    {
        // Ensure the hotel belongs to the authenticated owner
        if ($hotel->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Try removing from hotel_staff (cleaner/inspector)
        $removed = HotelStaff::where('hotel_id', $hotel->id)
            ->where('user_id', $user->id)
            ->delete();

        // Try removing from hotel_receptionists
        if (!$removed) {
            $removed = HotelReceptionist::where('hotel_id', $hotel->id)
                ->where('user_id', $user->id)
                ->delete();
        }

        if ($removed) {
            return redirect()->route('owner.staff.index', $hotel)
                ->with('success', $user->name . ' has been removed from ' . $hotel->name . '.');
        }

        return redirect()->route('owner.staff.index', $hotel)
            ->with('error', 'Staff member not found.');
    }

    /**
     * Update hourly wage for a staff member
     */
    public function updateWage(Request $request, Hotel $hotel, User $user): RedirectResponse
    {
        // Ensure the hotel belongs to the authenticated owner
        if ($hotel->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'hourly_rate' => 'required|numeric|min:0.01',
        ]);

        $staffRecord = HotelStaff::where('hotel_id', $hotel->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$staffRecord) {
            return back()->with('error', 'Staff record not found.');
        }

        $staffRecord->update(['hourly_rate' => $validated['hourly_rate']]);

        return redirect()->route('owner.staff.index', $hotel)
            ->with('success', 'Hourly rate updated for ' . $user->name . '.');
    }
}
