<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelReceptionist;
use App\Models\HotelStaff;
use App\Models\StaffApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HotelBrowseController extends Controller
{
    /**
     * Get the current user's staff role slug
     */
    private function getStaffRole(): string
    {
        return Auth::user()->role->slug;
    }

    /**
     * Browse all approved hotels with hourly wage info
     */
    public function index(): View
    {
        $user = Auth::user();
        $role = $this->getStaffRole();

        $hotels = Hotel::where('status', 'approved')
            ->where('is_verified', true)
            ->with('rooms')
            ->get();

        // Get user's existing applications
        $myApplications = StaffApplication::where('user_id', $user->id)
            ->pluck('status', 'hotel_id');

        // Get hotels where user already works
        $workingAtHotels = [];

        if ($role === 'receptionist') {
            $existing = HotelReceptionist::where('user_id', $user->id)->first();
            if ($existing) {
                $workingAtHotels[] = $existing->hotel_id;
            }
        } else {
            $workingAtHotels = HotelStaff::where('user_id', $user->id)
                ->where('role', $role)
                ->pluck('hotel_id')
                ->toArray();
        }

        // Check if receptionist is already assigned (block further applications)
        $isReceptionistBlocked = false;
        if ($role === 'receptionist') {
            $isReceptionistBlocked = HotelReceptionist::where('user_id', $user->id)->exists();
        }

        return view('staff.hotels.index', compact('hotels', 'myApplications', 'workingAtHotels', 'role', 'isReceptionistBlocked'));
    }

    /**
     * Show application form for a specific hotel
     */
    public function apply(Hotel $hotel): View|RedirectResponse
    {
        $user = Auth::user();
        $role = $this->getStaffRole();

        // Check if receptionist already works somewhere
        if ($role === 'receptionist') {
            $existing = HotelReceptionist::where('user_id', $user->id)->first();
            if ($existing) {
                return redirect()->route('staff.hotels.index')
                    ->with('error', 'You are already assigned to a hotel. Receptionists can only work at one hotel.');
            }
        }

        // Check if already working at this hotel
        if ($role === 'receptionist') {
            $alreadyWorking = HotelReceptionist::where('user_id', $user->id)
                ->where('hotel_id', $hotel->id)->exists();
        } else {
            $alreadyWorking = HotelStaff::where('user_id', $user->id)
                ->where('hotel_id', $hotel->id)
                ->where('role', $role)->exists();
        }

        if ($alreadyWorking) {
            return redirect()->route('staff.hotels.index')
                ->with('error', 'You are already working at this hotel.');
        }

        // Check if already has a pending application
        $existingApplication = StaffApplication::where('user_id', $user->id)
            ->where('hotel_id', $hotel->id)
            ->where('role', $role)
            ->where('status', 'pending')
            ->exists();

        if ($existingApplication) {
            return redirect()->route('staff.hotels.index')
                ->with('error', 'You already have a pending application for this hotel.');
        }

        return view('staff.hotels.apply', compact('hotel', 'role'));
    }

    /**
     * Store a staff application
     */
    public function storeApplication(Request $request, Hotel $hotel): RedirectResponse
    {
        $user = Auth::user();
        $role = $this->getStaffRole();

        $validated = $request->validate([
            'message' => 'nullable|string|max:500',
        ]);

        // Same validation checks as apply()
        if ($role === 'receptionist') {
            if (HotelReceptionist::where('user_id', $user->id)->exists()) {
                return redirect()->route('staff.hotels.index')
                    ->with('error', 'Receptionists can only work at one hotel.');
            }
        }

        // Check for existing pending application
        $existing = StaffApplication::where('user_id', $user->id)
            ->where('hotel_id', $hotel->id)
            ->where('role', $role)
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return redirect()->route('staff.my-applications')
                ->with('error', 'You already have a pending application for this hotel.');
        }

        StaffApplication::create([
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
            'role' => $role,
            'status' => 'pending',
            'message' => $validated['message'],
        ]);

        return redirect()->route('staff.my-applications')
            ->with('success', 'Application submitted! The hotel owner will review your application.');
    }

    /**
     * View my applications
     */
    public function myApplications(): View
    {
        $user = Auth::user();

        $applications = StaffApplication::where('user_id', $user->id)
            ->with('hotel', 'reviewer')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.my-applications', compact('applications'));
    }
}
