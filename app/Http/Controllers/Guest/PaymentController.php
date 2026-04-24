<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    /**
     * Show the payment form for a guest's booking.
     */
    public function showPaymentForm(Booking $booking): View
    {
        // Ensure the booking belongs to the logged-in user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $booking->load(['hotel', 'payments', 'bookingItems.room']);

        return view('guest.payments.form', [
            'booking' => $booking,
            'remainingBalance' => $booking->remainingBalance(),
        ]);
    }

    /**
     * Process a simulated payment.
     */
    public function processPayment(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'cardholder_name' => 'required|string|max:255',
            'card_number' => 'required|string|size:16',
            'expiry_date' => 'required|string|regex:/^\d{2}\/\d{2}$/',
            'cvv' => 'required|string|size:3',
        ]);

        $remainingBalance = $booking->remainingBalance();
        $paymentAmount = $request->amount;

        if ($paymentAmount > $remainingBalance) {
            return redirect()->back()->with('error', "Payment amount exceeds remaining balance of \${$remainingBalance}");
        }

        try {
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $paymentAmount,
                'payment_method' => 'credit_card',
                'type' => 'payment',
                'notes' => 'Online payment by guest',
                'processed_by' => Auth::id(),
                'payment_date' => now(),
            ]);

            // Update booking status if fully paid
            $newRemainingBalance = $booking->remainingBalance();
            if ($newRemainingBalance <= 0) {
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed' // Auto-confirm once paid
                ]);
            } else {
                $booking->update(['payment_status' => 'partial']);
            }

            return redirect()->route('guest.bookings.index')
                ->with('success', 'Payment successful! Your booking is now ' . $booking->status . '.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Submit a refund request.
     */
    public function requestRefund(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|string|min:10|max:1000',
        ]);

        $amountPaid = $booking->amountPaid();

        if ($request->amount > $amountPaid) {
            return redirect()->back()->with('error', "Refund amount exceeds total amount paid of \${$amountPaid}");
        }

        // Check if there's already a pending request
        $existingRequest = RefundRequest::where('booking_id', $booking->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You already have a pending refund request for this booking.');
        }

        try {
            RefundRequest::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            return redirect()->route('guest.bookings.index')
                ->with('success', 'Refund request submitted. A receptionist will review it shortly.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Could not submit refund request: ' . $e->getMessage());
        }
    }
}
