<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelRequestController extends Controller
{
    /**
     * Display all hotel requests (admin dashboard)
     */
    public function index()
    {
        $pendingRequests = HotelRequest::pending()
            ->with('owner')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $approvedRequests = HotelRequest::approved()
            ->with('owner', 'reviewer')
            ->orderBy('reviewed_at', 'desc')
            ->paginate(10);

        $rejectedRequests = HotelRequest::rejected()
            ->with('owner', 'reviewer')
            ->orderBy('reviewed_at', 'desc')
            ->paginate(10);

        return view('admin.hotel-requests.index', [
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
            'rejectedRequests' => $rejectedRequests,
        ]);
    }

    /**
     * Show a single hotel request
     */
    public function show(HotelRequest $request)
    {
        $request->load('owner', 'reviewer');

        return view('admin.hotel-requests.show', [
            'request' => $request,
        ]);
    }

    /**
     * Approve a hotel request
     */
    public function approve(Request $request, HotelRequest $hotelRequest)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($hotelRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $hotelRequest->approve(Auth::id(), $validated['admin_notes'] ?? null);

        return redirect()
            ->route('admin.hotel-requests.index')
            ->with('success', 'Hotel request approved successfully! Hotel has been created.');
    }

    /**
     * Reject a hotel request
     */
    public function reject(Request $request, HotelRequest $hotelRequest)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|min:10|max:1000',
        ]);

        if ($hotelRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $hotelRequest->reject(Auth::id(), $validated['admin_notes']);

        return redirect()
            ->route('admin.hotel-requests.index')
            ->with('success', 'Hotel request has been rejected.');
    }
}
