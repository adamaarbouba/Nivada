<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelsController extends Controller
{
    public function index(Request $request): View
    {
        $query = Hotel::where('status', 'approved')
            ->where('is_verified', true);

        // Search by name or city
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by price range
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $minPrice = (float) $request->input('min_price');
            $maxPrice = (float) $request->input('max_price');

            $query->whereHas('rooms', function ($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('price_per_night', [$minPrice, $maxPrice])
                    ->where('status', 'Available');
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        $hotels = $query->with('rooms')
            ->paginate(12);

        // Get cities for filter dropdown
        $cities = Hotel::where('status', 'approved')
            ->where('is_verified', true)
            ->distinct()
            ->pluck('city')
            ->sort();

        return view('guest.hotels.index', [
            'hotels' => $hotels,
            'cities' => $cities,
            'search' => $request->input('search'),
            'selectedCity' => $request->input('city'),
            'minPrice' => $request->input('min_price'),
            'maxPrice' => $request->input('max_price'),
        ]);
    }

    public function show(Hotel $hotel): View
    {
        $hotel->load('rooms', 'reviews');
        $availableRooms = $hotel->rooms()
            ->where('status', 'Available')
            ->get();

        return view('guest.hotels.show', [
            'hotel' => $hotel,
            'availableRooms' => $availableRooms,
        ]);
    }
}
