<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display all users with optional search (name, email, or role)
     */
    public function index(Request $request): View
    {
        $query = User::with('role')->whereHas('role', function ($q) {
            $q->where('slug', '!=', 'admin');
        });

        // Filter by role if provided
        if ($request->has('role') && $request->role !== '') {
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('slug', $request->role);
            });
        }

        // Search by name, email, or role if provided
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('role', function ($role) use ($search) {
                        $role->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $users = $query
            ->latest('created_at')
            ->paginate(15);

        // Get all available roles for filter
        $roles = \App\Models\Role::where('slug', '!=', 'admin')->orderBy('title')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Display specific user details with their hotel information
     */
    public function show(User $user): View
    {
        $user->load('role');

        $hotelInfo = null;

        // Get hotels based on user role
        if ($user->role->slug === 'owner') {
            // Owners have their own hotels
            $hotelInfo = $user->ownedHotels()->with('rooms')->get();
        } elseif ($user->role->slug === 'receptionist') {
            // Receptionists work at one hotel
            $receptionistRecord = $user->receptionistAt;
            if ($receptionistRecord) {
                $hotelInfo = $receptionistRecord->hotel()->with('rooms')->first();
            }
        } elseif (in_array($user->role->slug, ['cleaner', 'inspector'])) {
            // Cleaners and inspectors work at multiple hotels
            $hotelInfo = $user->hotels()->with('rooms')->get();
        }

        return view('admin.users.show', compact('user', 'hotelInfo'));
    }

    /**
     * Ban a user
     */
    public function ban(User $user)
    {
        // Prevent banning admin users
        if ($user->role->slug === 'admin') {
            return redirect()->back()->with('error', 'Cannot ban admin users.');
        }

        $user->update(['banned_at' => now()]);

        return redirect()->back()->with('success', $user->name . ' has been banned successfully.');
    }

    /**
     * Unban a user
     */
    public function unban(User $user)
    {
        $user->update(['banned_at' => null]);

        return redirect()->back()->with('success', $user->name . ' has been unbanned successfully.');
    }
}
