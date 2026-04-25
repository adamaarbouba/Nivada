@extends('layouts.app')

@php
    $pageTitle = 'Guest Dashboard';
@endphp

@section('content')
    <div class="mb-12">
        <p class="text-xs font-medium uppercase mb-2" style="color: #A0717F; letter-spacing: 0.4em;">
            Experience
        </p>
        <h1 class="text-3xl lg:text-5xl font-bold" style="color: #EAD3CD; font-family: 'Georgia', serif;">
            Guest Atelier
        </h1>
    </div>

    <!-- Guest Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#383537] rounded-lg shadow p-6 border border-[#4E3B46] border-t-4 border-t-[#A0717F]">
            <h3 class="text-sm font-semibold text-[#EAD3CD]">Current Bookings</h3>
            <p class="text-3xl font-bold mt-2 text-[#EAD3CD]">{{ $currentBookingsCount }}</p>
        </div>
        <div class="bg-[#383537] rounded-lg shadow p-6 border border-[#4E3B46] border-t-4 border-t-[#4E3B46]">
            <h3 class="text-sm font-semibold text-[#EAD3CD]">Past Stays</h3>
            <p class="text-3xl font-bold mt-2 text-[#EAD3CD]">{{ $pastBookingsCount }}</p>
        </div>
        <div class="bg-[#383537] rounded-lg shadow p-6 border border-[#4E3B46] border-t-4 border-t-[#4E3B46]">
            <h3 class="text-sm font-semibold text-[#EAD3CD]">Reviews</h3>
            <p class="text-3xl font-bold mt-2 text-[#EAD3CD]">{{ $reviewsCount }}</p>
        </div>
    </div>

    <!-- Guest Functions -->
    <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8 mb-8">
        <h3 class="text-xl font-semibold mb-6 text-[#EAD3CD]">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('guest.hotels.index') }}" class="p-4 border border-[#4E3B46] bg-[#383537] hover:bg-[#2A2729] rounded-lg transition hover:shadow-md">
                <h4 class="font-semibold text-[#A0717F]">Search Hotels</h4>
                <p class="text-sm mt-1 text-[#CFCBCA]">Browse and book hotels</p>
            </a>
            <a href="{{ route('guest.bookings.index') }}" class="p-4 border border-[#4E3B46] bg-[#383537] hover:bg-[#2A2729] rounded-lg transition hover:shadow-md">
                <h4 class="font-semibold text-[#EAD3CD]">My Bookings</h4>
                <p class="text-sm mt-1 text-[#CFCBCA]">View current and past bookings</p>
            </a>
            <a href="{{ route('guest.reviews.index') }}" class="p-4 border border-[#4E3B46] bg-[#383537] hover:bg-[#2A2729] rounded-lg transition hover:shadow-md">
                <h4 class="font-semibold text-[#A0717F]">My Reviews</h4>
                <p class="text-sm mt-1 text-[#CFCBCA]">Leave and view your reviews</p>
            </a>
        </div>
    </div>

    <!-- Current Bookings -->
    <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8 mb-8">
        <h3 class="text-xl font-semibold mb-6 text-[#EAD3CD]">Current Bookings</h3>
        @if ($currentBookings->count() > 0)
            <div class="space-y-4">
                @foreach ($currentBookings as $booking)
                    @php
                        $firstRoom = $booking->bookingItems->first()?->room;
                    @endphp
                    <div class="border border-[#4E3B46] bg-[#2A2729] rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-semibold text-[#EAD3CD]">
                                    {{ $booking->hotel->name ?? 'Unknown Hotel' }}</h4>
                                @if ($firstRoom)
                                    <p class="text-sm mt-1 text-[#CFCBCA]">Room {{ $firstRoom->room_number }}</p>
                                @endif
                            </div>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-[#EAD3CD]"
                                style="background-color:
                                  @if ($booking->status === 'pending') #A0717F
                                  @elseif($booking->status === 'confirmed') #1A1515
                                  @elseif($booking->status === 'checked_in') #1A1515 @else #4E3B46 @endif;">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        <div class="text-sm mb-2 text-[#CFCBCA]">
                            <p>Check-in: <strong class="text-[#EAD3CD]">{{ $booking->check_in_date->format('M d, Y') }}</strong></p>
                            <p>Check-out: <strong class="text-[#EAD3CD]">{{ $booking->check_out_date->format('M d, Y') }}</strong></p>
                        </div>
                        <p class="text-sm font-semibold text-[#A0717F]">
                            ${{ number_format($booking->total_amount, 2) }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-[#CFCBCA]">You have no current bookings. <a href="{{ route('guest.hotels.index') }}"
                    class="text-[#A0717F] hover:underline">Search hotels</a> to make a booking.</p>
        @endif
    </div>

    <!-- Past Stays -->
    <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8">
        <h3 class="text-xl font-semibold mb-6 text-[#EAD3CD]">Past Stays</h3>
        @if ($pastBookings->count() > 0)
            <div class="space-y-4">
                @foreach ($pastBookings as $booking)
                    @php
                        $firstRoom = $booking->bookingItems->first()?->room;
                    @endphp
                    <div class="border border-[#4E3B46] bg-[#2A2729] rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-semibold text-[#EAD3CD]">
                                    {{ $booking->hotel->name ?? 'Unknown Hotel' }}</h4>
                                @if ($firstRoom)
                                    <p class="text-sm mt-1 text-[#CFCBCA]">Room {{ $firstRoom->room_number }}</p>
                                @endif
                            </div>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-[#EAD3CD]"
                                style="background-color: 
                                  @if ($booking->status === 'completed' || $booking->status === 'checked_out') #1A1515
                                  @elseif($booking->status === 'cancelled') #4E3B46 @else #1A1515 @endif;">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        <div class="text-sm mb-2 text-[#CFCBCA]">
                            <p>Check-in: <strong class="text-[#EAD3CD]">{{ $booking->check_in_date->format('M d, Y') }}</strong></p>
                            <p>Check-out: <strong class="text-[#EAD3CD]">{{ $booking->check_out_date->format('M d, Y') }}</strong></p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-semibold text-[#A0717F]">
                                ${{ number_format($booking->total_amount, 2) }}</p>
                            @if ($booking->status === 'completed' || $booking->status === 'checked_out')
                                <a href="{{ route('guest.reviews.create', $booking) }}" class="text-sm text-[#A0717F] hover:underline">Leave Review →</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-[#CFCBCA]">No past stays yet. Your booking history will appear here.</p>
        @endif
    </div>

@endsection




