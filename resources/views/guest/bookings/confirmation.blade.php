@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="max-w-2xl mx-auto px-4 py-12">
        <!-- Success Message -->
        <div class="bg-[#1A1515] border border-green-500 rounded-lg p-6 mb-8">
            <div class="flex items-center gap-3 mb-2">
                <x-icon name="checkmark" size="md" class="text-green-400" />
                <h2 class="text-xl font-bold text-green-400">Booking Confirmed!</h2>
            </div>
            <p class="text-green-400">Your booking has been successfully created. Booking ID:
                <strong>#{{ $booking->id }}</strong>
            </p>
        </div>

        <!-- Booking Details Card -->
        <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-bold text-[#EAD3CD] mb-6">Booking Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Hotel & Room Info -->
                <div>
                    <h4 class="text-sm font-semibold text-[#CFCBCA] mb-3">Hotel & Room</h4>
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs text-[#CFCBCA]">Hotel</p>
                            <p class="text-lg font-semibold text-[#EAD3CD]">{{ $booking->hotel->name }}</p>
                        </div>
                        @php
                            $firstItem = $booking->bookingItems->first();
                            $room = $firstItem?->room;
                        @endphp
                        @if ($room)
                            <div>
                                <p class="text-xs text-[#CFCBCA]">Room Number</p>
                                <p class="text-[#EAD3CD]">{{ $room->room_number }} ({{ $room->room_type }})
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-[#CFCBCA]">Capacity</p>
                                <p class="text-[#EAD3CD]">{{ $room->capacity }} Guests</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Dates -->
                <div>
                    <h4 class="text-sm font-semibold text-[#CFCBCA] mb-3">Stay Dates</h4>
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs text-[#CFCBCA]">Check-in</p>
                            <p class="text-lg font-semibold text-[#EAD3CD]">
                                {{ $booking->check_in_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-[#CFCBCA]">Check-out</p>
                            <p class="text-lg font-semibold text-[#EAD3CD]">
                                {{ $booking->check_out_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-[#CFCBCA]">Duration</p>
                            <p class="text-[#EAD3CD]">{{ $booking->check_in_date->diff($booking->check_out_date)->days }}
                                nights</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Special Requests -->
            @if ($booking->special_requests)
                <div class="mt-6 pt-6 border-t border-[#4E3B46]">
                    <h4 class="text-sm font-semibold text-[#CFCBCA] mb-2">Special Requests</h4>
                    <p class="text-[#EAD3CD]">{{ $booking->special_requests }}</p>
                </div>
            @endif
        </div>

        <!-- Cost Breakdown -->
        <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8 mb-8">
            <h3 class="text-lg font-bold text-[#EAD3CD] mb-6">Cost Breakdown</h3>

            <div class="space-y-3 pb-4 border-b border-[#4E3B46]">
                <div class="flex justify-between">
                    @php
                        $nights = $booking->check_in_date->diff($booking->check_out_date)->days;
                        $pricePerNight = $firstItem?->price_per_night ?? 0;
                    @endphp
                    <span class="text-[#CFCBCA]">{{ $nights }}
                        nights × ${{ number_format($pricePerNight, 2) }}/night</span>
                    <span class="font-semibold text-[#EAD3CD]">${{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>

            <div class="flex justify-between items-center pt-4">
                <span class="text-lg font-semibold text-[#EAD3CD]">Total Amount</span>
                <span class="text-3xl font-bold text-[#A0717F]">${{ number_format($booking->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-[#2A2729] border border-[#A0717F] rounded-lg p-6 mb-8">
            <h4 class="text-lg font-semibold text-[#EAD3CD] mb-2">Booking Status</h4>
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-[#1A1515] text-yellow-400 border border-yellow-500">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
            <p class="text-[#CFCBCA] text-sm">Your booking is currently <strong class="text-[#EAD3CD]">pending</strong>. You need to complete
                the payment to confirm your reservation.</p>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('guest.payments.form', $booking) }}"
                class="flex-1 bg-[#A0717F] hover:bg-[#8F6470] text-[#EAD3CD] px-6 py-3 rounded-lg font-semibold text-center transition">
                Make Payment
            </a>
            <a href="{{ route('guest.hotels.index') }}"
                class="flex-1 bg-[#4E3B46] hover:bg-[#68525F] text-[#EAD3CD] border border-[#4E3B46] px-6 py-3 rounded-lg font-semibold text-center transition">
                Continue Shopping
            </a>
        </div>
    </div>
@endsection
