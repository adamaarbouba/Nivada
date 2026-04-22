<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    /**
     * Display a specific hotel with all its details (Admin view)
     */
    public function show(Hotel $hotel): View
    {
        // Admin can view any hotel
        $hotel->load('rooms', 'owner');

        return view('admin.hotels.show', compact('hotel'));
    }
}
