@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-[#EAD3CD]">My Bookings</h2>
            <a href="{{ route('guest.dashboard') }}"
                class="bg-[#4E3B46] hover:bg-[#68525F] text-[#EAD3CD] border border-[#4E3B46] px-4 py-2 rounded transition">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Current Bookings Section -->
        <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-[#EAD3CD] flex items-center gap-3">
                    <x-icon name="calendar" size="md" class="text-[#A0717F]" />
                    Current Bookings
                    <span class="text-sm font-normal text-[#CFCBCA]">({{ $currentBookings->count() }})</span>
                </h3>
                <a href="{{ route('guest.hotels.index') }}"
                    class="bg-[#A0717F] hover:bg-[#8F6470] text-[#EAD3CD] px-4 py-2 rounded transition">
                    + Book Hotel
                </a>
            </div>

            @if ($currentBookings->count() > 0)
                <div class="grid grid-cols-1 gap-6">
                    @foreach ($currentBookings as $booking)
                        @php
                            $firstItem = $booking->bookingItems->first();
                            $room = $firstItem?->room;
                            $nights = $booking->check_in_date->diff($booking->check_out_date)->days;
                        @endphp
                        <div class="border border-[#4E3B46] bg-[#2A2729] rounded-lg p-6 hover:shadow-lg transition">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <!-- Hotel Info -->
                                <div>
                                    <h4 class="text-sm font-semibold text-[#CFCBCA] mb-1">Hotel</h4>
                                    <p class="text-lg font-bold text-[#EAD3CD]">{{ $booking->hotel->name }}</p>
                                    @if ($booking->hotel->city)
                                        <p class="text-sm text-[#CFCBCA]">{{ $booking->hotel->city }}</p>
                                    @endif
                                </div>

                                <!-- Room Info -->
                                <div>
                                    <h4 class="text-sm font-semibold text-[#CFCBCA] mb-1">Room</h4>
                                    @if ($room)
                                        <p class="text-lg font-bold text-[#EAD3CD]">{{ $room->room_number }}</p>
                                        <p class="text-sm text-[#CFCBCA]">{{ $room->room_type }}</p>
                                    @else
                                        <p class="text-[#CFCBCA]">Room info not available</p>
                                    @endif
                                </div>

                                <!-- Dates -->
                                <div>
                                    <h4 class="text-sm font-semibold text-[#CFCBCA] mb-1">Dates</h4>
                                    <p class="text-sm text-[#EAD3CD]">
                                        <strong>Check-in:</strong> {{ $booking->check_in_date->format('M d, Y') }}
                                    </p>
                                    <p class="text-sm text-[#EAD3CD]">
                                        <strong>Check-out:</strong>
                                        {{ $booking->check_out_date->format('M d, Y') }}
                                    </p>
                                    <p class="text-sm text-[#A0717F] font-semibold">{{ $nights }} nights</p>
                                </div>

                                <!-- Cost & Status -->
                                <div>
                                    <h4 class="text-sm font-semibold text-[#CFCBCA] mb-1">Details</h4>
                                    <p class="text-2xl font-bold text-[#A0717F]">
                                        ${{ number_format($booking->total_amount, 2) }}
                                    </p>
                                    <div class="mt-2">
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            @if ($booking->status === 'pending') bg-[#1A1515] text-yellow-400 border border-yellow-500
                                            @elseif($booking->status === 'confirmed') bg-[#1A1515] text-green-400 border border-green-500
                                            @elseif($booking->status === 'checked_in') bg-[#1A1515] text-blue-400 border border-blue-500 @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                        <span
                                            class="inline-block ml-2 px-3 py-1 rounded-full text-xs font-semibold
                                            @if ($booking->payment_status === 'pending') bg-[#1A1515] text-orange-400 border border-orange-500
                                            @elseif($booking->payment_status === 'paid') bg-[#1A1515] text-green-400 border border-green-500
                                            @elseif($booking->payment_status === 'partial') bg-[#1A1515] text-yellow-400 border border-yellow-500
                                            @elseif($booking->payment_status === 'refunded') bg-[#1A1515] text-[#CFCBCA] border border-[#4E3B46] @endif">
                                            Payment: {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Special Requests -->
                            @if ($booking->special_requests)
                                <div class="mt-4 pt-4 border-t border-[#4E3B46]">
                                    <h5 class="text-sm font-semibold text-[#CFCBCA] mb-2">Special Requests</h5>
                                    <p class="text-[#EAD3CD] text-sm">{{ $booking->special_requests }}</p>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="mt-4 pt-4 border-t border-[#4E3B46] flex flex-wrap gap-4 items-center">
                                <a href="{{ route('guest.bookings.confirmation', $booking) }}"
                                    class="text-[#A0717F] hover:text-[#EAD3CD] text-sm font-semibold flex items-center gap-2">
                                    <x-icon name="search" size="xs" />
                                    View Details
                                </a>

                                @if($booking->remainingBalance() > 0)
                                    <a href="{{ route('guest.payments.form', $booking) }}"
                                        class="bg-[#A0717F] hover:bg-[#8F6470] text-white text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-lg transition-all shadow-lg flex items-center gap-2">
                                        <x-icon name="credit-card" size="xs" />
                                        Pay Now
                                    </a>
                                @else
                                    <a href="{{ route('guest.payments.form', $booking) }}"
                                        class="border border-[#4E3B46] text-[#CFCBCA] hover:bg-[#4E3B46] text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-lg transition-all flex items-center gap-2">
                                        <x-icon name="sparkles" size="xs" />
                                        Manage Payment / Refund
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-[#CFCBCA] text-lg mb-4">You have no current bookings</p>
                    <a href="{{ route('guest.hotels.index') }}"
                        class="inline-block bg-[#A0717F] hover:bg-[#8F6470] text-[#EAD3CD] px-6 py-2 rounded transition">
                        Search Hotels & Book Now
                    </a>
                </div>
            @endif
        </div>

        <!-- Past Bookings Section -->
        <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8">
            <h3 class="text-2xl font-bold text-[#EAD3CD] mb-6 flex items-center gap-3">
                <x-icon name="history" size="md" class="text-[#A0717F]" />
                Past Stays
                <span class="text-sm font-normal text-[#CFCBCA]">({{ $pastBookings->count() }})</span>
            </h3>

            @if ($pastBookings->count() > 0)
                <div class="grid grid-cols-1 gap-6">
                    @foreach ($pastBookings as $booking)
                        @php
                            $firstItem = $booking->bookingItems->first();
                            $room = $firstItem?->room;
                            $nights = $booking->check_in_date->diff($booking->check_out_date)->days;
                        @endphp
                        <div class="border border-[#4E3B46] bg-[#2A2729] rounded-lg p-6 hover:shadow-lg transition">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <!-- Hotel Info -->
                                <div>
                                    <h4 class="text-sm font-semibold text-[#CFCBCA] mb-1">Hotel</h4>
                                    <p class="text-lg font-bold text-[#EAD3CD]">{{ $booking->hotel->name }}</p>
                                    @if ($booking->hotel->city)
                                        <p class="text-sm text-[#CFCBCA]">{{ $booking->hotel->city }}</p>
                                    @endif
                                </div>

                                <!-- Room Info -->
                                <div>
                                    <h4 class="text-sm font-semibold text-[#CFCBCA] mb-1">Room</h4>
                                    @if ($room)
                                        <p class="text-lg font-bold text-[#EAD3CD]">{{ $room->room_number }}</p>
                                        <p class="text-sm text-[#CFCBCA]">{{ $room->room_type }}</p>
                                    @else
                                        <p class="text-[#CFCBCA]">Room info not available</p>
                                    @endif
                                </div>

                                <!-- Dates -->
                                <div>
                                    <h4 class="text-sm font-semibold text-[#CFCBCA] mb-1">Dates</h4>
                                    <p class="text-sm text-[#EAD3CD]">
                                        <strong>Check-in:</strong> {{ $booking->check_in_date->format('M d, Y') }}
                                    </p>
                                    <p class="text-sm text-[#EAD3CD]">
                                        <strong>Check-out:</strong>
                                        {{ $booking->check_out_date->format('M d, Y') }}
                                    </p>
                                    <p class="text-sm text-[#CFCBCA]">{{ $nights }} nights</p>
                                </div>

                                <!-- Cost & Status -->
                                <div>
                                    <h4 class="text-sm font-semibold text-[#CFCBCA] mb-1">Details</h4>
                                    <p class="text-2xl font-bold text-[#A0717F]">
                                        ${{ number_format($booking->total_amount, 2) }}
                                    </p>
                                    <div class="mt-2">
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            @if ($booking->status === 'completed') bg-[#1A1515] text-green-400 border border-green-500
                                            @elseif($booking->status === 'checked_out') bg-[#1A1515] text-[#CFCBCA] border border-[#4E3B46]
                                            @elseif($booking->status === 'cancelled') bg-[#1A1515] text-red-400 border border-red-500 @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Special Requests -->
                            @if ($booking->special_requests)
                                <div class="mt-4 pt-4 border-t border-[#4E3B46]">
                                    <h5 class="text-sm font-semibold text-[#CFCBCA] mb-2">Special Requests</h5>
                                    <p class="text-[#EAD3CD] text-sm">{{ $booking->special_requests }}</p>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="mt-4 pt-4 border-t border-[#4E3B46] flex gap-3">
                                @if ($booking->status === 'completed' || $booking->status === 'checked_out')
                                    <a href="{{ route('guest.reviews.create', $booking) }}"
                                        class="bg-[#A0717F] hover:bg-[#8F6470] text-white text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-lg transition-all shadow-lg flex items-center gap-2">
                                        <x-icon name="star" size="xs" />
                                        Leave Review
                                    </a>
                                @endif
                                <a href="{{ route('guest.bookings.confirmation', $booking) }}"
                                    class="text-[#A0717F] hover:text-[#EAD3CD] text-sm font-semibold">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-[#CFCBCA] text-lg">No past stays yet. Your booking history will appear here.</p>
                </div>
            @endif
        </div>

</div>@endsection
